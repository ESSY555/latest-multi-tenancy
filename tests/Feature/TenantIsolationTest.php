<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Branch;
use App\Models\StudentProfile;
use App\Tenancy\BranchContext;
use App\Exceptions\TenantContextMissingException;
use App\Exceptions\CrossTenantOperationException;
use App\Models\User;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure context is clean before each test
        BranchContext::clear();
    }

    public function test_missing_context_throws_exception()
    {
        $this->expectException(TenantContextMissingException::class);
        
        // This should throw because we didn't set a branch context
        StudentProfile::all();
    }

    public function test_tenant_reads_are_isolated()
    {
        $branchA = Branch::factory()->create(['name' => 'Branch A']);
        $branchB = Branch::factory()->create(['name' => 'Branch B']);

        // Temporarily bypass to seed data
        BranchContext::bypass();
        $studentA = StudentProfile::factory()->create(['branch_id' => $branchA->id]);
        $studentB = StudentProfile::factory()->create(['branch_id' => $branchB->id]);
        BranchContext::bypass(false);

        // Set context to A
        BranchContext::setBranchId($branchA->id);

        $students = StudentProfile::all();
        $this->assertCount(1, $students);
        $this->assertEquals($studentA->id, $students->first()->id);

        // Set context to B
        BranchContext::setBranchId($branchB->id);
        
        $students = StudentProfile::all();
        $this->assertCount(1, $students);
        $this->assertEquals($studentB->id, $students->first()->id);
    }

    public function test_tenant_cannot_create_cross_tenant_record()
    {
        $branchA = Branch::factory()->create(['name' => 'Branch A']);
        $branchB = Branch::factory()->create(['name' => 'Branch B']);

        BranchContext::setBranchId($branchA->id);

        $this->expectException(CrossTenantOperationException::class);
        
        // Try to create a student for Branch B while in Branch A context
        StudentProfile::factory()->create(['branch_id' => $branchB->id]);
    }

    public function test_tenant_cannot_update_cross_tenant_record()
    {
        $branchA = Branch::factory()->create(['name' => 'Branch A']);
        $branchB = Branch::factory()->create(['name' => 'Branch B']);

        BranchContext::bypass();
        $studentA = StudentProfile::factory()->create(['branch_id' => $branchA->id]);
        BranchContext::bypass(false);

        BranchContext::setBranchId($branchA->id);

        $this->expectException(CrossTenantOperationException::class);
        
        // Try to move student from A to B
        $studentA->branch_id = $branchB->id;
        $studentA->save();
    }

    public function test_system_wide_access_bypasses_scope()
    {
        $branchA = Branch::factory()->create(['name' => 'Branch A']);
        $branchB = Branch::factory()->create(['name' => 'Branch B']);

        BranchContext::bypass();
        StudentProfile::factory()->create(['branch_id' => $branchA->id]);
        StudentProfile::factory()->create(['branch_id' => $branchB->id]);
        BranchContext::bypass(false);

        // Verify without bypass it throws
        try {
            StudentProfile::all();
            $this->fail("Should have thrown TenantContextMissingException");
        } catch (TenantContextMissingException $e) {
            $this->assertTrue(true);
        }

        // Use withoutGlobalScope explicitly
        $allStudents = StudentProfile::withoutGlobalScope(\App\Models\Scopes\BranchScope::class)->get();
        
        $this->assertCount(2, $allStudents);
    }
}
