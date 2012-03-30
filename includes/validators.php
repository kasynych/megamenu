<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lanos
 * Date: 29.03.12
 * Time: 10:19
 * To change this template use File | Settings | File Templates.
 */
// --------------------------------------------------------------------

/**
 * Minimum Length
 *
 * @access	public
 * @param	string
 * @param	value
 * @return	bool
 */
function min_length($str, $val)
{
    if (preg_match("/[^0-9]/", $val))
    {
        return FALSE;
    }

    if (function_exists('mb_strlen'))
    {
        return (mb_strlen($str) < $val) ? FALSE : TRUE;
    }

    return (strlen($str) < $val) ? FALSE : TRUE;
}

// --------------------------------------------------------------------

/**
 * Max Length
 *
 * @access	public
 * @param	string
 * @param	value
 * @return	bool
 */
function max_length($str, $val)
{
    if (preg_match("/[^0-9]/", $val))
    {
        return FALSE;
    }

    if (function_exists('mb_strlen'))
    {
        return (mb_strlen($str) > $val) ? FALSE : TRUE;
    }

    return (strlen($str) > $val) ? FALSE : TRUE;
}

// --------------------------------------------------------------------

/**
 * Exact Length
 *
 * @access	public
 * @param	string
 * @param	value
 * @return	bool
 */
function exact_length($str, $val)
{
    if (preg_match("/[^0-9]/", $val))
    {
        return FALSE;
    }

    if (function_exists('mb_strlen'))
    {
        return (mb_strlen($str) != $val) ? FALSE : TRUE;
    }

    return (strlen($str) != $val) ? FALSE : TRUE;
}

// --------------------------------------------------------------------

/**
 * Valid Email
 *
 * @access	public
 * @param	string
 * @return	bool
 */
function valid_email($str)
{
    return ( ! preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
}

// --------------------------------------------------------------------

/**
 * Valid Emails
 *
 * @access	public
 * @param	string
 * @return	bool
 */
function valid_emails($str)
{
    if (strpos($str, ',') === FALSE)
    {
        return $this->valid_email(trim($str));
    }

    foreach (explode(',', $str) as $email)
    {
        if (trim($email) != '' && $this->valid_email(trim($email)) === FALSE)
        {
            return FALSE;
        }
    }

    return TRUE;
}

// --------------------------------------------------------------------

/**
 * Validate IP Address
 *
 * @access	public
 * @param	string
 * @return	string
 */
function valid_ip($ip)
{
    return $this->CI->input->valid_ip($ip);
}

// --------------------------------------------------------------------

/**
 * Alpha
 *
 * @access	public
 * @param	string
 * @return	bool
 */
function alpha($str)
{
    return ( ! preg_match("/^([a-z])+$/i", $str)) ? FALSE : TRUE;
}

// --------------------------------------------------------------------

/**
 * Alpha-numeric
 *
 * @access	public
 * @param	string
 * @return	bool
 */
function alpha_numeric($str)
{
    return ( ! preg_match("/^([a-z0-9])+$/i", $str)) ? FALSE : TRUE;
}

// --------------------------------------------------------------------

/**
 * Alpha-numeric with underscores and dashes
 *
 * @access	public
 * @param	string
 * @return	bool
 */
function alpha_dash($str)
{
    return ( ! preg_match("/^([-a-z0-9_-])+$/i", $str)) ? FALSE : TRUE;
}

// --------------------------------------------------------------------

/**
 * Numeric
 *
 * @access	public
 * @param	string
 * @return	bool
 */
function numeric($str)
{
    return (bool)preg_match( '/^[\-+]?[0-9]*\.?[0-9]+$/', $str);

}


// --------------------------------------------------------------------

/**
 * Integer
 *
 * @access	public
 * @param	string
 * @return	bool
 */
function integer($str)
{
    return (bool) preg_match('/^[\-+]?[0-9]+$/', $str);
}

// --------------------------------------------------------------------

/**
 * Decimal number
 *
 * @access	public
 * @param	string
 * @return	bool
 */
function decimal($str)
{
    return (bool) preg_match('/^[\-+]?[0-9]+\.[0-9]+$/', $str);
}

// --------------------------------------------------------------------

/**
 * Greather than
 *
 * @access	public
 * @param	string
 * @return	bool
 */
function greater_than($str, $min)
{
    if ( ! is_numeric($str))
    {
        return FALSE;
    }
    return $str > $min;
}

// --------------------------------------------------------------------

/**
 * Less than
 *
 * @access	public
 * @param	string
 * @return	bool
 */
function less_than($str, $max)
{
    if ( ! is_numeric($str))
    {
        return FALSE;
    }
    return $str < $max;
}

// --------------------------------------------------------------------

/**
 * Is a Natural number  (0,1,2,3, etc.)
 *
 * @access	public
 * @param	string
 * @return	bool
 */
function is_natural($str)
{
    return (bool) preg_match( '/^[0-9]+$/', $str);
}

// --------------------------------------------------------------------

/**
 * Is a Natural number, but not a zero  (1,2,3, etc.)
 *
 * @access	public
 * @param	string
 * @return	bool
 */
function is_natural_no_zero($str)
{
    if ( ! preg_match( '/^[0-9]+$/', $str))
    {
        return FALSE;
    }

    if ($str == 0)
    {
        return FALSE;
    }

    return TRUE;
}

// --------------------------------------------------------------------

/**
 * Valid Base64
 *
 * Tests a string for characters outside of the Base64 alphabet
 * as defined by RFC 2045 http://www.faqs.org/rfcs/rfc2045
 *
 * @access	public
 * @param	string
 * @return	bool
 */
function valid_base64($str)
{
    return (bool) ! preg_match('/[^a-zA-Z0-9\/\+=]/', $str);
}

function is_valid_url($url)
{
    return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}