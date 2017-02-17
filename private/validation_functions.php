<?php

  // is_blank('abcd')
  function is_blank($value='') {
    return !isset($value) || trim($value) == '';
  }

  // has_length('abcd', ['min' => 3, 'max' => 5])
  function has_length($value, $options=array()) {
    $length = strlen($value);
    if(isset($options['max']) && ($length > $options['max'])) {
      return false;
    } elseif(isset($options['min']) && ($length < $options['min'])) {
      return false;
    } elseif(isset($options['exact']) && ($length != $options['exact'])) {
      return false;
    } else {
      return true;
    }
  }

  // has_valid_email_format('test@test.com')
  function has_valid_email_format($value) {
    // Function can be improved later to check for
    // more than just '@'.
    return filter_var($value, FILTER_VALIDATE_EMAIL);
    //return strpos($value, '@') !== false;
  }

  // no longer than 255 characters for db entries
  function has_valid_length($value) {
    return has_length($value, ['min' => 1, 'max' => 255]);
  }

  // checks that username only has whitelisted characters
  function has_valid_username_whitelist($value) {
    return preg_match('/\A[A-Za-z\d\_]+\Z/', $value);
  }

  // checks that phone numbers only contain whitelisted characters
  function has_valid_phone_number_whitelist($value) {
    return preg_match('/\A[\d\-\(\)\s]+\Z/', $value);
  }

  // checks that email contains only whitelisted characters
  function has_valid_email_whitelist($value) {
    return preg_match('/\A[A-Za-z\d\@\.\-\_]+\Z/', $value);
  }

  // My Custom Validation
  // checks names only have letters hyphens and spaces
  function has_valid_name_format($value) {
    return preg_match('/\A[A-Za-z\-\s]+\Z/', $value);
  }

  // My Custom Validation
  // validates that the username is not already taken
  function has_unique_username($value) {

  }

  // My Custon Validation
  // checks phone numbers if they are of the correct format
  // Both US and canada has the same (3 digita area code) 7 digit number
  // This function will just check if there are 10 numeric digits
  function has_valid_phone_number_format($value) {

  }

?>
