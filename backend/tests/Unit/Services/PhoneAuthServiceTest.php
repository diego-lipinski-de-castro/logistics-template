<?php

namespace Tests\Unit\Services;

use App\Services\PhoneAuthService;
use Tests\TestCase;

class PhoneAuthServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_verify_valid_phone()
    {
        $phone = '+5547991532336';

        $result = (new PhoneAuthService())->verify($phone);

        $this->assertNotNull($result);
        $this->assertIsObject($result);
        $this->assertObjectHasAttribute('phoneNumber', $result);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_verify_invalid_phone()
    {
        $phone = '123';

        $result = (new PhoneAuthService())->verify($phone);

        $this->assertNull($result);
    }

    // /**
    //  * A basic unit test example.
    //  *
    //  * @return void
    //  */
    // public function test_verify_valid_code()
    // {
    //     $phone = '+5547991532336';

    //     $result = (new PhoneAuthService())->verify($phone);

    //     $result = (new PhoneAuthService())->check($result['phoneNumber'], null);

    //     $this->assertNotNull($result);
    //     $this->assertIsObject($result);

    //     $this->assertObjectHasAttribute('phoneNumber', $result);
    //     $this->assertObjectHasAttribute('status', $result);
    //     $this->assertObjectHasAttribute('valid', $result);
    //     $this->assertObjectHasAttribute('dateUpdated', $result);
    // }
}
