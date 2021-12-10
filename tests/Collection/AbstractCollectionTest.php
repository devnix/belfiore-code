<?php

namespace Devnix\BelfioreCode\Collection;

use PHPUnit\Framework\TestCase;

class AbstractCollectionTest extends TestCase
{
    public function testGroupBy(): void
    {
        $comuneCollection = $stub = $this->getMockForAbstractClass(
            AbstractCollection::class,
            [
                [
                    [
                        'id' => '12560',
                        'institution_date' => '1866-11-19',
                        'end_date' => '1924-11-13',
                        'istat_id' => '028001',
                        'registry_code' => 'A001',
                        'name_it' => 'ABANO',
                        'name_transliterated' => 'ABANO',
                        'alternative_name' => '',
                        'alternative_name_transliterated' => '',
                        'anpr_id' => '28',
                        'istat_province_id' => '028',
                        'istat_region_id' => '05',
                        'istat_prefecture_id' => '',
                        'status' => 'discontinued',
                        'provincial_code' => 'PD',
                        'source' => '',
                        'last_update' => '2016-06-17',
                        'istat_discontinued_code' => '028500',
                    ],
                    [
                        'id' => '12560',
                        'institution_date' => '1866-11-19',
                        'end_date' => '1924-11-13',
                        'istat_id' => '028001',
                        'registry_code' => 'A001',
                        'name_it' => 'ABANO',
                        'name_transliterated' => 'ABANO',
                        'alternative_name' => '',
                        'alternative_name_transliterated' => '',
                        'anpr_id' => '28',
                        'istat_province_id' => '028',
                        'istat_region_id' => '05',
                        'istat_prefecture_id' => '',
                        'status' => 'discontinued',
                        'provincial_code' => 'PD',
                        'source' => '',
                        'last_update' => '2016-06-17',
                        'istat_discontinued_code' => '028500',
                    ],
                    [
                        'id' => '12560',
                        'institution_date' => '1866-11-19',
                        'end_date' => '1924-11-13',
                        'istat_id' => '028001',
                        'registry_code' => 'A002',
                        'name_it' => 'FOO BAR',
                        'name_transliterated' => 'FOO BAR',
                        'alternative_name' => '',
                        'alternative_name_transliterated' => '',
                        'anpr_id' => '28',
                        'istat_province_id' => '028',
                        'istat_region_id' => '05',
                        'istat_prefecture_id' => '',
                        'status' => 'discontinued',
                        'provincial_code' => 'PD',
                        'source' => '',
                        'last_update' => '2016-06-17',
                        'istat_discontinued_code' => '028500',
                    ]
                ]
            ]
        );

        $this->assertCount(2, $comuneCollection->groupBy('name_it'));

        $this->assertEqualsCanonicalizing(
            [
                [
                    'id' => '12560',
                    'institution_date' => '1866-11-19',
                    'end_date' => '1924-11-13',
                    'istat_id' => '028001',
                    'registry_code' => 'A001',
                    'name_it' => 'ABANO',
                    'name_transliterated' => 'ABANO',
                    'alternative_name' => '',
                    'alternative_name_transliterated' => '',
                    'anpr_id' => '28',
                    'istat_province_id' => '028',
                    'istat_region_id' => '05',
                    'istat_prefecture_id' => '',
                    'status' => 'discontinued',
                    'provincial_code' => 'PD',
                    'source' => '',
                    'last_update' => '2016-06-17',
                    'istat_discontinued_code' => '028500',
                ],
                [
                    'id' => '12560',
                    'institution_date' => '1866-11-19',
                    'end_date' => '1924-11-13',
                    'istat_id' => '028001',
                    'registry_code' => 'A002',
                    'name_it' => 'FOO BAR',
                    'name_transliterated' => 'FOO BAR',
                    'alternative_name' => '',
                    'alternative_name_transliterated' => '',
                    'anpr_id' => '28',
                    'istat_province_id' => '028',
                    'istat_region_id' => '05',
                    'istat_prefecture_id' => '',
                    'status' => 'discontinued',
                    'provincial_code' => 'PD',
                    'source' => '',
                    'last_update' => '2016-06-17',
                    'istat_discontinued_code' => '028500',
                ]
            ],
            $comuneCollection->groupBy('name_it')->toArray()
        );

        $this->assertCount(1, $comuneCollection->groupBy('id'));

        $this->assertEqualsCanonicalizing(
            [
                [
                    'id' => '12560',
                    'institution_date' => '1866-11-19',
                    'end_date' => '1924-11-13',
                    'istat_id' => '028001',
                    'registry_code' => 'A001',
                    'name_it' => 'ABANO',
                    'name_transliterated' => 'ABANO',
                    'alternative_name' => '',
                    'alternative_name_transliterated' => '',
                    'anpr_id' => '28',
                    'istat_province_id' => '028',
                    'istat_region_id' => '05',
                    'istat_prefecture_id' => '',
                    'status' => 'discontinued',
                    'provincial_code' => 'PD',
                    'source' => '',
                    'last_update' => '2016-06-17',
                    'istat_discontinued_code' => '028500',
                ]
            ],
            $comuneCollection->groupBy('id')->toArray()
        );
    }
}
