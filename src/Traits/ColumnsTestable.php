<?php


namespace Thomasdominic\ModelsTestor\Traits;



use Illuminate\Support\Facades\Schema;

trait ColumnsTestable
{
    /** @test */
    public function table_has_expected_columns()
    {
        if(property_exists($this,'table') && property_exists($this,'columns')
            && count($this->columns) > 0 && !empty($this->table) )  {
            $this->assertEquals([], collect($this->columns)->diffAssoc(Schema::getColumnListing($this->table))->toArray());
        }
        else{
            $this->assertTrue(true);
        }
    }
}