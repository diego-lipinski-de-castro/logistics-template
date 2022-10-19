<?php

namespace Tests\Unit\Services;

use App\Helper;
use Tests\TestCase;

class HelperTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_extract_numbers_from_string()
    {
        $input = null;
        $result = Helper::extractNumbersFromString($input);
        $this->assertSame(0, $result);

        $input = 'R$ 10,00';
        $result = Helper::extractNumbersFromString($input);
        $this->assertSame(1000, $result);

        $input = 'R$ 10.000,00';
        $result = Helper::extractNumbersFromString($input);
        $this->assertSame(1000000, $result);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_to_phone()
    {
        $input = '+55 (47) 991532336';
        $result = Helper::toPhone($input);
        $this->assertSame('+5547991532336', $result);

        $input = '+5547991532336';
        $result = Helper::toPhone($input);
        $this->assertSame('+5547991532336', $result);

        $input = '5547991532336';
        $result = Helper::toPhone($input);
        $this->assertSame('+5547991532336', $result);

        $input = '(47) 991532336';
        $result = Helper::toPhone($input);
        $this->assertSame('+47991532336', $result);

        $input = '+47991532336';
        $result = Helper::toPhone($input);
        $this->assertSame('+47991532336', $result);

        $input = '47991532336';
        $result = Helper::toPhone($input);
        $this->assertSame('+47991532336', $result);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_to_phone_with_country_code()
    {
        $input = '+55 (47) 991532336';
        $result = Helper::toPhone($input, true);
        $this->assertSame('+555547991532336', $result);

        $input = '+5547991532336';
        $result = Helper::toPhone($input, true);
        $this->assertSame('+555547991532336', $result);

        $input = '5547991532336';
        $result = Helper::toPhone($input, true);
        $this->assertSame('+555547991532336', $result);

        $input = '(47) 991532336';
        $result = Helper::toPhone($input, true);
        $this->assertSame('+5547991532336', $result);

        $input = '+47991532336';
        $result = Helper::toPhone($input, true);
        $this->assertSame('+5547991532336', $result);

        $input = '47991532336';
        $result = Helper::toPhone($input, true);
        $this->assertSame('+5547991532336', $result);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_to_money()
    {
        $input = 1;
        $result = Helper::toMoney($input);
        $this->assertSame('R$ 0,01', $result);

        $input = 10;
        $result = Helper::toMoney($input);
        $this->assertSame('R$ 0,10', $result);

        $input = 100;
        $result = Helper::toMoney($input);
        $this->assertSame('R$ 1,00', $result);

        $input = 1000;
        $result = Helper::toMoney($input);
        $this->assertSame('R$ 10,00', $result);

        $input = 10000;
        $result = Helper::toMoney($input);
        $this->assertSame('R$ 100,00', $result);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_to_decimal()
    {
        $input = 1;
        $result = Helper::toDecimal($input);
        $this->assertSame('0,01', $result);

        $input = 10;
        $result = Helper::toDecimal($input);
        $this->assertSame('0,10', $result);

        $input = 100;
        $result = Helper::toDecimal($input);
        $this->assertSame('1,00', $result);

        $input = 1000;
        $result = Helper::toDecimal($input);
        $this->assertSame('10,00', $result);

        $input = 10000;
        $result = Helper::toDecimal($input);
        $this->assertSame('100,00', $result);
    }
}
