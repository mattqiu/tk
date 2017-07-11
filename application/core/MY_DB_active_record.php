<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_DB_active_record extends CI_DB_active_record
{
    /**
     * Get SELECT query string
     *
     * Compiles a SELECT query string and returns the sql.
     *
     * @param	string	the table name to select from (optional)
     * @param	bool	TRUE: resets QB values; FALSE: leave QB values alone
     * @return	string
     */
    public function get_compiled_select($table = '', $reset = TRUE)
    {
        if ($table !== '')
        {
            $this->_track_aliases($table);
            $this->from($table);
        }
        $select = $this->_compile_select();
        if ($reset === TRUE)
        {
            $this->_reset_select();
        }
        return $select;
    }

}