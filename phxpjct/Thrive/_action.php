<?php
  include_once "_select_functions.php";
  $action = $_GET['action'];

  if ("get_district_pastor" == $action) {
    $district_pk = $_GET['district_pk'];
    $condition = $_GET['condition'];
    $value = $_GET['value'];

    $result = get_thrive_district_pastor_by_condition($district_pk,$condition,$value);
    $count = 1;
    while ($pastor = pg_fetch_assoc($result)) {
      $pastor_pk = $pastor['id'];
      $pastor_id = "P".str_pad($pastor_pk, 6, 0, STR_PAD_LEFT);
      $last_name = $pastor['lastname'];
      $first_name = $pastor['firstname'];
      $membership_status = $pastor['member'];
      $membership_date = $pastor['membershipdate'];

      echo "<span class='default_a'>$count</span>. <a href='profile_edit.php?district=$district_pk&pastor_pk=$pastor_pk'>($pastor_id)</a> $last_name, $first_name  $membership_status | $membership_date ",flag_date_accuracy($membership_date),"<br/>";
      $count++;
    }
  }

  if ("get_pastor_profile" == $action) {
    $pastor_pk = $_GET['pastor_pk'];
    $condition = $_GET['condition'];
    $pastor = get_pastor_profile($pastor_pk);
    $pastor_id = "P".str_pad($pastor_pk, 6, 0, STR_PAD_LEFT);
    $today = date("Y-m-d");
    $last_name = $pastor['lastname'];
    $first_name = $pastor['firstname'];
    $middle_name = $pastor['middlename'];
    $gender = $pastor['gender'];
    $birthday = $pastor['birthday'];
    $birthday_proper = date("M j, Y",strtotime(str_replace('-','/', $birthday)));
    $marital_status = $pastor['status'];
    $address = $pastor['address'];
    $province = $pastor['province'];
    $city = $pastor['city'];
    $barangay = $pastor['barangay'];
    $contact_1 = $pastor['contact1'];
    $contact_2 = $pastor['contact2'];
    $contact_3 = $pastor['contact3'];
    $email = $pastor['email'];
    $education = $pastor['education'];
    $education_graduate_bool = $pastor['education_graduated'];
    ($education_graduate_bool == "t") ? $education_graduate = 'Yes' : $education_graduate = '';
    ($education_graduate_bool == "f") ? $education_graduate = 'No' : $education_graduate = '';
    $thrive_pk = $pastor['thriveid'];
    $seminary_bool = $pastor['seminary'];
    ($seminary_bool == "t") ? $seminary = 'Yes' : $seminary = '';
    ($seminary_bool == "f") ? $seminary = 'No' : $seminary = '';
    $active_bool = $pastor['active'];
    ($active_bool == "t") ? $active = 'Yes' : $active = '';
    ($active_bool == "f") ? $active = 'No' : $active = '';
    $member_bool = $pastor['member'];
    ($member_bool == "t") ? $member = 'Yes' : $member = '';
    ($member_bool == "f") ? $member = 'No' : $member = '';
    $membership_date = $pastor['membershipdate'];
    ($membership_date != '') ? $membership_date_proper = date("M j, Y",strtotime(str_replace('-','/', $membership_date))) : $membership_date_proper = '-';
    $updated_by = $pastor['updatedby'];
    $updated_date = $pastor['updateddate'];
    ($updated_date != '') ? $updated_date_proper = date("M j, Y (G:i)",strtotime(str_replace('-','/', $updated_date))) : $updated_date_proper = '-';
    $church_position = $pastor['position'];
    $church_pk = $pastor['churchid'];
    $base_id = $pastor['baseid'];

    if ("view" == $condition) {
      echo
       "<fieldset>
        <label class='profile_label'>Pastor ID</label> <span class='profile_value'>$pastor_id</span>
        <br/>
        <label class='profile_label'>Last Name</label> <span class='profile_value'>$last_name</span>
        <br/>
        <label class='profile_label'>First Name</label> <span class='profile_value'>$first_name</span>
        <br/>
        <label class='profile_label'>Middle Name</label> <span class='profile_value'>$middle_name</span>
        <br/>
        <label class='profile_label'>Gender</label> <span class='profile_value'>$gender</span>
        <br/>
        <label class='profile_label'>Birthday</label> <span class='profile_value'>$birthday_proper</span>
        <br/>
        <label class='profile_label'>Marital Status</label> <span class='profile_value'>$marital_status</span>
        <br/>
        <label class='profile_label'>Address</label> <span class='profile_value'>$address</span>
        <br/>
        <label class='profile_label'>Province</label> <span class='profile_value'>$province</span>
        <br/>
        <label class='profile_label'>City/Municipality</label> <span class='profile_value'>$city</span>
        <br/>
        <label class='profile_label'>Barangay</label> <span class='profile_value'>$barangay</span>
        <br/>
        <label class='profile_label'>Contact Number 1</label> <span class='profile_value'>$contact_1</span>
        <br/>
        <label class='profile_label'>Contact Number 2</label> <span class='profile_value'>$contact_2</span>
        <br/>
        <label class='profile_label'>Contact Number 3</label> <span class='profile_value'>$contact_3</span>
        <br/>
        <label class='profile_label'>Email</label> <span class='profile_value'>$email</span>
        <br/>
        <label class='profile_label'>Education</label> <span class='profile_value'>$education</span>
        <br/>
        <label class='profile_label'>Graduated</label> <span class='profile_value'>$education_graduate</span>
        <br/>
        <label class='profile_label'>Seminary</label> <span class='profile_value'>$seminary</span>
        <br/>
        <label class='profile_label'>Church</label> <span class='profile_value'>$church_pk</span>
        <br/>
        <label class='profile_label'>Church Position</label> <span class='profile_value'>$church_position</span>
      </fieldset>
      <br/>
      <fieldset>
        <label class='profile_label'>Base</label> $base_id
        <br/>
        <label class='profile_label'>Thrive District</label> $thrive_pk
        <br/>
        <label class='profile_label'>Active</label>$active
        <br/>
        <label class='profile_label'>Member</label>$member
        <br/>
        <label class='profile_label'>Membership Date</label> $membership_date_proper
        <br/>
        <label class='profile_label'>Last Updated By</label> $updated_by
        <br/>
        <label class='profile_label'>Last Updated Date</label> $updated_date_proper
      </fieldset>";
    }

    else if ("edit" == $condition) {
      ($gender == "Male") ? $gender_male = "checked" : $gender_male = "";
      ($gender == "Female") ? $gender_female = "checked" : $gender_female = "";
      ($education_graduate_bool == "t") ? $education_graduate_yes = "checked" : $education_graduate_yes = "";
      ($education_graduate_bool == "f") ? $education_graduate_no = "checked" : $education_graduate_no = "";
      ($seminary_bool == "t") ? $seminary_yes = "checked" : $seminary_yes = "";
      ($seminary_bool == "f") ? $seminary_no = "checked" : $seminary_no = "";
      echo
       "<fieldset>
       $education_graduate_bool
        <label class='profile_label'>Pastor ID</label>
          <span class='profile_value'>$pastor_id</span>
          <br/>
        <label class='profile_label'>Last Name</label>
          <input type='text' value='$last_name'/>
          <br/>
        <label class='profile_label'>First Name</label>
          <input type='text' value='$first_name'/>
          <br/>
        <label class='profile_label'>Middle Name</label>
          <input type='text' value='$middle_name'/>
          <br/>
        <label class='profile_label'>Gender</label>
          <input type='radio' name='gender' $gender_male value='Male'> Male
          <input type='radio' name='gender' $gender_female value='Female'> Female
          <br/>
        <label class='profile_label'>Birthday</label>
          <input type='date' max='$today' value='$birthday'/>
          <br/>
        <label class='profile_label'>Marital Status</label>
          <select id='status' class='mdl-select__input' name='status'>
  					<option disabled selected value='$marital_status'>$marital_status</option>
  					<option value='Single'>Single</option>
  					<option value='Married'>Married</option>
  					<option value='Widowed'>Widowed</option>
  					<option value='Separated'>Separated</option>
  					<option value='Empty'>Not Indicated</option>
  				</select>
          <br/>
        <label class='profile_label'>Address</label>
          <input type='text' value='$address'/>
          <br/>
        <label class='profile_label'>Province</label>
          <input type='text' value='$province'/>
          <br/>
        <label class='profile_label'>City/Municipality</label>
          <input type='text' value='$city'/>
          <br/>
        <label class='profile_label'>Barangay</label>
          <input type='text' value='$barangay'/>
          <br/>
        <label class='profile_label'>Contact Number 1</label>
          <input type='text' value='$contact_1'/>
          <br/>
        <label class='profile_label'>Contact Number 2</label>
          <input type='text' value='$contact_2'/>
          <br/>
        <label class='profile_label'>Contact Number 3</label>
          <input type='text' value='$contact_3'/>
          <br/>
        <label class='profile_label'>Email</label>
          <input type='text' value='$email'/>
          <br/>
        <label class='profile_label'>Education</label>
          <select id='education_level' name='education_level' onChange='showGraduate_input(this.value)'>
  					<option disabled selected value='$education'>$education</option>
  					<option value='None'>None</option>
  					<option value='Elementary'>Elementary</option>
  					<option value='High School'>High School</option>
  					<option value='College'>College</option>
  					<option value='Post College'>Post College</option>
  					<option value='Empty'>Not Indicated</option>
  				</select>
          <br/>
        <label class='profile_label'>Graduated</label>
          <input type='radio' name='education_graduate' $education_graduate_yes value='t'> Yes
          <input type='radio' name='education_graduate' $education_graduate_no value='f'> No
          <br/>
        <label class='profile_label'>Seminary</label>
          <input type='radio' name='seminary' $seminary_yes value='t'> Yes
          <input type='radio' name='seminary' $seminary_no value='f'> No
          <br/>
        <label class='profile_label'>Church</label>
          <span class='profile_value'>$church_pk</span>
          <br/>
        <label class='profile_label'>Church Position</label>
          <span class='profile_value'>$church_position</span>
      </fieldset>
      <br/>
      <fieldset>
        <label class='profile_label'>Base</label> $base_id
        <br/>
        <label class='profile_label'>Thrive District</label> $thrive_pk
        <br/>
        <label class='profile_label'>Active</label>$active
        <br/>
        <label class='profile_label'>Member</label>$member
        <br/>
        <label class='profile_label'>Membership Date</label> $membership_date_proper
        <br/>
        <label class='profile_label'>Last Updated By</label> $updated_by
        <br/>
        <label class='profile_label'>Last Updated Date</label> $updated_date_proper
      </fieldset>";
    }

  }
?>
