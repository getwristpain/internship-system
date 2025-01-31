<?php

namespace App\Services;

use Throwable;
use App\Helpers\Logger;
use App\Models\Department;
use App\Services\SchoolService;
use Illuminate\Support\Facades\Validator;

class DepartmentService extends SchoolService
{
    /**
     * Find a department by its ID, returning a new instance if not found.
     *
     * @param int $id
     * @return Department
     */
    public static function findDepartment(?int $id): Department
    {
        try {
            $school = self::firstSchool();
            return Department::with('school')
                ->where('school_id', $school->id)
                ->find($id) ?? new Department(['school_id' => $school->id]);
        } catch (\Throwable $th) {
            Logger::handle('error', 'Failed when finding department data.', $th);
            throw $th;
        }
    }

    /**
     * Retrieve all departments for the current school.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Department[]
     */
    public static function getDepartments()
    {
        try {
            $school = self::firstSchool();
            return Department::with('classrooms')
                ->where('school_id', $school->id)
                ->get() ?? collect([new Department(['school_id' => $school->id])]);
        } catch (Throwable $th) {
            Logger::handle('error', 'Failed to retrieve department data.', $th);
            throw $th;
        }
    }

    /**
     * Store a new department.
     *
     * @param array $departmentData
     * @return bool
     */
    public static function storeDepartment(array $departmentData): bool
    {
        if (!self::validateDepartmentData($departmentData)) {
            return false;
        }

        try {
            $school = self::firstSchool();
            $department = Department::create([
                'code' => $departmentData['code'],
                'name' => $departmentData['name'],
                'school_id' => $school->id,
            ]);

            return (bool)$department;
        } catch (Throwable $th) {
            Logger::handle('error', 'Failed while saving department data.', $th);
            throw $th;
        }
    }

    /**
     * Validate department data.
     *
     * @param array $departmentData
     * @return bool
     */
    protected static function validateDepartmentData(array $departmentData): bool
    {
        $rules = [
            'code' => 'required|string|unique:departments,code',
            'name' => 'required|string|min:5|max:255',
        ];

        $validator = Validator::make($departmentData, $rules);

        if ($validator->fails()) {
            Logger::handle('error', 'Failed when validating department data.', new \Exception(json_encode($validator->errors()->getMessages())));
            return false;
        }

        return true;
    }

    public static function deleteDepartment(int $id): bool
    {
        try {
            $department = Department::find($id);
            if ($department->delete()) {
                return true;
            }

            return false;
        } catch (\Throwable $th) {
            Logger::handle('error', 'Failed when deleting department', $th);
            throw $th;
        }
    }
}
