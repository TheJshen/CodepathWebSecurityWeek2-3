<?php

  //
  // COUNTRY QUERIES
  //

  // Find all countries, ordered by name
  function find_all_countries() {
    global $db;
    $sql = "SELECT * FROM countries ORDER BY name ASC;";
    $country_result = db_query($db, $sql);
    return $country_result;
  }

  //
  // STATE QUERIES
  //

  // Find all states, ordered by name
  function find_all_states() {
    global $db;
    $sql = "SELECT * FROM states ";
    $sql .= "ORDER BY name ASC;";
    $state_result = db_query($db, $sql);
    return $state_result;
  }

  // Find all states, ordered by name
  function find_states_for_country_id($country_id=0) {
    global $db;
    $sql = "SELECT * FROM states ";
    $sql .= "WHERE country_id='" . $db->real_escape_string($country_id) . "' ";
    $sql .= "ORDER BY name ASC;";
    $state_result = db_query($db, $sql);
    return $state_result;
  }

  // Find state by ID
  function find_state_by_id($id=0) {
    global $db;
    $sql = "SELECT * FROM states ";
    $sql .= "WHERE id='" . $db->real_escape_string($id) . "';";
    $state_result = db_query($db, $sql);
    return $state_result;
  }

  function validate_state($state, $errors=array()) {
    if (is_blank($state['name'])) {
      $errors[] = "Name cannot be blank.";
    } elseif (!has_length($state['name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "Name must be between 2 and 255 characters.";
    } elseif(!has_valid_name_format($state['name'])) {
      $errors[] = "Name must contain letters, spaces, and the symbol '-'.";
    }

    if (is_blank($state['code'])) {
      $errors[] = "State code cannot be blank.";
    } elseif (!has_length($state['code'], array('min' => 2, 'max' => 2))) {
      $errors[] = "State code must be between 2 characters.";
    } elseif(!has_valid_state_code_format($state['code'])) {
      $errors[] = "Must only contain capital letters.";
    }

    if (is_blank($state['country_id'])) {
      $errors[] = "Country ID cannot be blank.";
    } elseif (!has_valid_country_id_format($state['country_id'])) {
      $errors[] = "Country ID must be a number.";
    } elseif (is_negative_num($state['country_id'])) {
      $errors[] = "Country ID must be a positive number.";
    }

    return $errors;
  }

  // Add a new state to the table
  // Either returns true or an array of errors
  function insert_state($state) {
    global $db;

    $errors = validate_state($state);
    if (!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO states ";
    $sql .= "(name, code, country_id) ";
    $sql .= "VALUES (";
    $sql .= "'" . $db->real_escape_string($state['name']) . "',";
    $sql .= "'" . $db->real_escape_string($state['code']) . "',";
    $sql .= "'" . $db->real_escape_string($state['country_id']) . "'";
    $sql .= ");";
    // For INSERT statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL INSERT statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Edit a state record
  // Either returns true or an array of errors
  function update_state($state) {
    global $db;

    $errors = validate_state($state);
    if (!empty($errors)) {
      return $errors;
    }

    $sql = "UPDATE states SET ";
    $sql .= "name='" . $db->real_escape_string($state['name']) . "', ";
    $sql .= "code='" . $db->real_escape_string($state['code']) . "', ";
    $sql .= "country_id='" . $db->real_escape_string($state['country_id']) . "' ";
    $sql .= "WHERE id='" . $db->real_escape_string($state['id']) . "' ";
    $sql .= "LIMIT 1;";
    // For update_state statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL UPDATE statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  //
  // TERRITORY QUERIES
  //

  // Find all territories, ordered by state_id
  function find_all_territories() {
    global $db;
    $sql = "SELECT * FROM territories ";
    $sql .= "ORDER BY state_id ASC, position ASC;";
    $territory_result = db_query($db, $sql);
    return $territory_result;
  }

  // Find all territories whose state_id (foreign key) matches this id
  function find_territories_for_state_id($state_id=0) {
    global $db;
    $sql = "SELECT * FROM territories ";
    $sql .= "WHERE state_id='" . $db->real_escape_string($state_id) . "' ";
    $sql .= "ORDER BY position ASC;";
    $territory_result = db_query($db, $sql);
    return $territory_result;
  }

  // Find territory by ID
  function find_territory_by_id($id=0) {
    global $db;
    $sql = "SELECT * FROM territories ";
    $sql .= "WHERE id='" . $db->real_escape_string($id) . "';";
    $territory_result = db_query($db, $sql);
    return $territory_result;
  }

  function validate_territory($territory, $errors=array()) {
    // TODO add validations
    if (is_blank($territory['name'])) {
      $errors[] = "Territory name cannot be blank.";
    } elseif (!has_valid_length($territory['name'])) {
      $errors[] = "Territory name must be between 1 and 255 characters.";
    } elseif (!has_valid_name_format($territory['name'])) {
      $errors[] = "Territory name must only contain letters, spaces, and the symbol '-'.";
    }

    if (is_blank($territory['state_id'])) {
      $errors[] = "State ID cannot be left blank.";
    } elseif (!has_valid_country_id_format($territory['state_id'])) {
      $errors[] = "State ID needs to be a number.";
    } elseif (is_negative_num($territory['state_id'])) {
      $errors[] = "State ID needs to be a positive number.";
    }

    if (is_blank($territory['position'])) {
      $errors[] = "Position cannot be left blank.";
    } elseif (!has_valid_country_id_format($territory['position'])) {
      $errors[] = "Position needs to be a number.";
    } elseif (is_negative_num($territory['position'])) {
      $errors[] = "Position needs to be a positive number.";
    }

    return $errors;
  }

  // Add a new territory to the table
  // Either returns true or an array of errors
  function insert_territory($territory) {
    global $db;

    $errors = validate_territory($territory);
    if (!empty($errors)) {
      return $errors;
    }

    $sql = "INSERT INTO territories ";
    $sql .= "(name, state_id, position) ";
    $sql .= "VALUES (";
    $sql .= "'" . $db->real_escape_string($territory['name']) . "',";
    $sql .= "'" . $db->real_escape_string($territory['state_id']) . "',";
    $sql .= "'" . $db->real_escape_string($territory['position']) . "'";
    $sql .= ");";
    // For INSERT statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL INSERT territoryment failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Edit a territory record
  // Either returns true or an array of errors
  function update_territory($territory) {
    global $db;

    $errors = validate_territory($territory);
    if (!empty($errors)) {
      return $errors;
    }

    $sql = "UPDATE territories SET ";
    $sql .= "name='" . $db->real_escape_string($territory['name']) . "',";
    $sql .= "state_id='" . $db->real_escape_string($territory['state_id']) . "',";
    $sql .= "position='" . $db->real_escape_string($territory['position']) . "' ";
    $sql .= "WHERE id='" . $db->real_escape_string($territory['id']) . "' ";
    $sql .= "LIMIT 1;";
    // For update_territory statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL UPDATE territoryment failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  //
  // SALESPERSON QUERIES
  //

  // Find all salespeople, ordered last_name, first_name
  function find_all_salespeople() {
    global $db;
    $sql = "SELECT * FROM salespeople ";
    $sql .= "ORDER BY last_name ASC, first_name ASC;";
    $salespeople_result = db_query($db, $sql);
    return $salespeople_result;
  }

  // To find salespeople, we need to use the join table.
  // We LEFT JOIN salespeople_territories and then find results
  // in the join table which have the same territory ID.
  function find_salespeople_for_territory_id($territory_id=0) {
    global $db;
    $sql = "SELECT * FROM salespeople ";
    $sql .= "LEFT JOIN salespeople_territories
              ON (salespeople_territories.salesperson_id = salespeople.id) ";
    $sql .= "WHERE salespeople_territories.territory_id='" . $db->real_escape_string($territory_id) . "' ";
    $sql .= "ORDER BY last_name ASC, first_name ASC;";
    $salespeople_result = db_query($db, $sql);
    return $salespeople_result;
  }

  // Find salesperson using id
  function find_salesperson_by_id($id=0) {
    global $db;
    $sql = "SELECT * FROM salespeople ";
    $sql .= "WHERE id='" . $db->real_escape_string($id) . "';";
    $salespeople_result = db_query($db, $sql);
    return $salespeople_result;
  }

  function validate_salesperson($salesperson, $errors=array()) {
    if (is_blank($salesperson['first_name'])) {
      $errors[] = "First name cannot be blank.";
    } elseif (!has_length($salesperson['first_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "First name must be between 2 and 255 characters.";
    } elseif(!has_valid_name_format($salesperson['first_name'])) {
      $errors[] = "First name must contain letters, spaces, and the symbol '-'.";
    }

    if (is_blank($salesperson['last_name'])) {
      $errors[] = "Last name cannot be blank.";
    } elseif (!has_length($salesperson['last_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "Last name must be between 2 and 255 characters.";
    } elseif(!has_valid_name_format($salesperson['last_name'])) {
      $errors[] = "Last name must contain letters, spaces, and the symbol '-'.";
    }

    if (is_blank($salesperson['email'])) {
      $errors[] = "Email cannot be blank.";
    } elseif(!has_valid_length($salesperson['email'])) {
      $errors[] = "E-Mail must contain less than 255 characters.";
    } elseif (!has_valid_email_format($salesperson['email'])) {
      $errors[] = "Email must be a valid format.";
    }

    if (is_blank($salesperson['phone'])) {
      $errors[] = "Phone number cannot be blank.";
    } elseif(!has_valid_phone_number_whitelist($salesperson['phone'])) {
      $errors[] = "Phone must contain only numbers, space, and the symbol '-'.";
    } elseif(!has_valid_phone_number_format($salesperson['phone'])) {
      $errors[] = "Phone number must contain area code (3-digits) and LSN (7-digits).";
    }

    return $errors;
  }

  // Add a new salesperson to the table
  // Either returns true or an array of errors
  function insert_salesperson($salesperson) {
    global $db;

    $errors = validate_salesperson($salesperson);
    if (!empty($errors)) {
      return $errors;
    }


    $sql = "INSERT INTO salespeople ";
    $sql .= "(first_name, last_name, phone, email) ";
    $sql .= "VALUES (";
    $sql .= "'" . $db->real_escape_string($salesperson['first_name']) . "',";
    $sql .= "'" . $db->real_escape_string($salesperson['last_name']) . "',";
    $sql .= "'" . $db->real_escape_string($salesperson['phone']) . "',";
    $sql .= "'" . $db->real_escape_string($salesperson['email']) . "'";
    $sql .= ");";
    // For INSERT statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL INSERT statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Edit a salesperson record
  // Either returns true or an array of errors
  function update_salesperson($salesperson) {
    global $db;

    $errors = validate_salesperson($salesperson);
    if (!empty($errors)) {
      return $errors;
    }

    $sql = "UPDATE salespeople SET ";
    $sql .= "first_name='" . $db->real_escape_string($salesperson['first_name']) . "', ";
    $sql .= "last_name='" . $db->real_escape_string($salesperson['last_name']) . "', ";
    $sql .= "phone='" . $db->real_escape_string($salesperson['phone']) . "', ";
    $sql .= "email='" . $db->real_escape_string($salesperson['email']) . "' ";
    $sql .= "WHERE id='" . $db->real_escape_string($salesperson['id']) . "' ";
    $sql .= "LIMIT 1;";
    // For update_salesperson statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL UPDATE statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // To find territories, we need to use the join table.
  // We LEFT JOIN salespeople_territories and then find results
  // in the join table which have the same salesperson ID.
  function find_territories_by_salesperson_id($id=0) {
    global $db;
    $sql = "SELECT * FROM territories ";
    $sql .= "LEFT JOIN salespeople_territories
              ON (territories.id = salespeople_territories.territory_id) ";
    $sql .= "WHERE salespeople_territories.salesperson_id='" . $db->real_escape_string($id) . "' ";
    $sql .= "ORDER BY territories.name ASC;";
    $territories_result = db_query($db, $sql);
    return $territories_result;
  }

  //
  // USER QUERIES
  //

  // Find all users, ordered last_name, first_name
  function find_all_users() {
    global $db;
    $sql = "SELECT * FROM users ";
    $sql .= "ORDER BY last_name ASC, first_name ASC;";
    $users_result = db_query($db, $sql);
    return $users_result;
  }

  // Find user using id
  function find_user_by_id($id=0) {
    global $db;
    $sql = "SELECT * FROM users WHERE id='" . $db->real_escape_string($id) . "' LIMIT 1;";
    $users_result = db_query($db, $sql);
    return $users_result;
  }

  function validate_user($user, $errors=array()) {
    if (is_blank($user['first_name'])) {
      $errors[] = "First name cannot be blank.";
    } elseif (!has_length($user['first_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "First name must be between 2 and 255 characters.";
    } elseif(!has_valid_name_format($user['first_name'])) {
      $errors[] = "First name must contain letters, spaces, and the symbol '-'.";
    }

    if (is_blank($user['last_name'])) {
      $errors[] = "Last name cannot be blank.";
    } elseif (!has_length($user['last_name'], array('min' => 2, 'max' => 255))) {
      $errors[] = "Last name must be between 2 and 255 characters.";
    } elseif(!has_valid_name_format($user['last_name'])) {
      $errors[] = "Last name must contain letters, spaces, and the symbol '-'.";
    }

    if (is_blank($user['email'])) {
      $errors[] = "Email cannot be blank.";
    } elseif(!has_valid_length($user['email'])) {
      $errors[] = "E-Mail must contain less than 255 characters.";
    } elseif (!has_valid_email_format($user['email'])) {
      $errors[] = "Email must be a valid format.";
    }

    if (is_blank($user['username'])) {
      $errors[] = "Username cannot be blank.";
    } elseif (!has_length($user['username'], array('max' => 255))) {
      $errors[] = "Username must be less than 255 characters.";
    } elseif(!has_valid_username_whitelist($user['username'])) {
      $errors[] = "Username must only contain letters, numbers, and the symbols '_'.";
    }

    return $errors;
  }

  // Add a new user to the table
  // Either returns true or an array of errors
  function insert_user($user) {
    global $db;

    $errors = validate_user($user);
    if (!empty($errors)) {
      return $errors;
    }

    $created_at = date("Y-m-d H:i:s");
    $sql = "INSERT INTO users ";
    $sql .= "(first_name, last_name, email, username, created_at) ";
    $sql .= "VALUES (";
    $sql .= "'" . $db->real_escape_string($user['first_name']) . "',";
    $sql .= "'" . $db->real_escape_string($user['last_name']) . "',";
    $sql .= "'" . $db->real_escape_string($user['email']) . "',";
    $sql .= "'" . $db->real_escape_string($user['username']) . "',";
    $sql .= "'" . $created_at . "'";
    $sql .= ");";
    // For INSERT statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL INSERT statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

  // Edit a user record
  // Either returns true or an array of errors
  function update_user($user) {
    global $db;

    $errors = validate_user($user);
    if (!empty($errors)) {
      return $errors;
    }

    $sql = "UPDATE users SET ";
    $sql .= "first_name='" . $db->real_escape_string($user['first_name']) . "', ";
    $sql .= "last_name='" . $db->real_escape_string($user['last_name']) . "', ";
    $sql .= "email='" . $db->real_escape_string($user['email']) . "', ";
    $sql .= "username='" . $db->real_escape_string($user['username']) . "' ";
    $sql .= "WHERE id='" . $db->real_escape_string($user['id']) . "' ";
    $sql .= "LIMIT 1;";
    // For update_user statments, $result is just true/false
    $result = db_query($db, $sql);
    if($result) {
      return true;
    } else {
      // The SQL UPDATE statement failed.
      // Just show the error, not the form
      echo db_error($db);
      db_close($db);
      exit;
    }
  }

?>
