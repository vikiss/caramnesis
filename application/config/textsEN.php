<?php

/**
 * Texts used in the application.
 * These texts are used via Text::get('FEEDBACK_USERNAME_ALREADY_TAKEN').
 * Could be extended to i18n etc.
 */
return array(
	"FEEDBACK_UNKNOWN_ERROR" => "Unknown error occurred!",
	"FEEDBACK_DELETED" => "Your account has been deleted.",
	"FEEDBACK_ACCOUNT_SUSPENDED" => "Account Suspended for ",
	"FEEDBACK_ACCOUNT_SUSPENSION_DELETION_STATUS" => "This user's suspension / deletion status has been edited.",
	"FEEDBACK_PASSWORD_WRONG_3_TIMES" => "You have typed in a wrong password 3 or more times already. Please wait 30 seconds to try again.",
	"FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET" => "Your account is not activated yet. Please click on the confirm link in the mail.",
	"FEEDBACK_USERNAME_OR_PASSWORD_WRONG" => "The username or password is incorrect. Please try again.",
	"FEEDBACK_USER_DOES_NOT_EXIST" => "This user does not exist.",
	"FEEDBACK_LOGIN_FAILED" => "Login failed.",
	"FEEDBACK_LOGIN_FAILED_3_TIMES" => "Login failed 3 or more times already. Please wait 30 seconds to try again.",
	"FEEDBACK_USERNAME_FIELD_EMPTY" => "Username field was empty.",
	"FEEDBACK_PASSWORD_FIELD_EMPTY" => "Password field was empty.",
	"FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY" => "Username or password field was empty.",
	"FEEDBACK_USERNAME_EMAIL_FIELD_EMPTY" => "Username / email field was empty.",
	"FEEDBACK_EMAIL_FIELD_EMPTY" => "Email field was empty.",
	"FEEDBACK_EMAIL_AND_PASSWORD_FIELDS_EMPTY" => "Email and password fields were empty.",
	"FEEDBACK_USERNAME_SAME_AS_OLD_ONE" => "Sorry, that username is the same as your current one. Please choose another one.",
	"FEEDBACK_USERNAME_ALREADY_TAKEN" => "Sorry, that username is already taken. Please choose another one.",
	"FEEDBACK_USER_EMAIL_ALREADY_TAKEN" => "Sorry, that email is already in use. Please choose another one.",
	"FEEDBACK_USERNAME_CHANGE_SUCCESSFUL" => "Your username has been changed successfully.",
	"FEEDBACK_USERNAME_AND_PASSWORD_FIELD_EMPTY" => "Username and password fields were empty.",
	"FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN" => "Username does not fit the name pattern: only a-Z and numbers are allowed, 2 to 64 characters.",
	"FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN" => "Sorry, your chosen email does not fit into the email naming pattern.",
	"FEEDBACK_EMAIL_SAME_AS_OLD_ONE" => "Sorry, that email address is the same as your current one. Please choose another one.",
	"FEEDBACK_EMAIL_CHANGE_SUCCESSFUL" => "Your email address has been changed successfully.",
	"FEEDBACK_CAPTCHA_WRONG" => "The entered captcha security characters were wrong.",
	"FEEDBACK_PASSWORD_REPEAT_WRONG" => "Password and password repeat are not the same.",
	"FEEDBACK_PASSWORD_TOO_SHORT" => "Password has a minimum length of 6 characters.",
	"FEEDBACK_USERNAME_TOO_SHORT_OR_TOO_LONG" => "Username cannot be shorter than 2 or longer than 64 characters.",
	"FEEDBACK_ACCOUNT_SUCCESSFULLY_CREATED" => "Your account has been created successfully and we have sent you an email. Please click the VERIFICATION LINK within that mail.",
	"FEEDBACK_VERIFICATION_MAIL_SENDING_FAILED" => "Sorry, we could not send you an verification mail. Your account has NOT been created.",
	"FEEDBACK_ACCOUNT_CREATION_FAILED" => "Sorry, your registration failed. Please go back and try again.",
	"FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR" => "Verification mail could not be sent due to: ",
	"FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL" => "A verification mail has been sent successfully.",
	"FEEDBACK_ACCOUNT_ACTIVATION_SUCCESSFUL" => "Activation was successful! You can now log in.",
	"FEEDBACK_ACCOUNT_ACTIVATION_FAILED" => "Sorry, no such id/verification code combination here...",
	"FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL" => "Avatar upload was successful.",
	"FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE" => "Only JPEG and PNG files are supported.",
	"FEEDBACK_AVATAR_UPLOAD_TOO_SMALL" => "Avatar source file's width/height is too small. Needs to be 100x100 pixel minimum.",
	"FEEDBACK_AVATAR_UPLOAD_TOO_BIG" => "Avatar source file is too big. 5 Megabyte is the maximum.",
	"FEEDBACK_AVATAR_FOLDER_DOES_NOT_EXIST_OR_NOT_WRITABLE" => "Avatar folder does not exist or is not writable. Please change this via chmod 775 or 777.",
	"FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED" => "Something went wrong with the image upload.",
	"FEEDBACK_AVATAR_IMAGE_DELETE_SUCCESSFUL" => "You successfully deleted your avatar.",
  "FEEDBACK_AVATAR_IMAGE_DELETE_NO_FILE" => "You don't have a custom avatar.",
  "FEEDBACK_AVATAR_IMAGE_DELETE_FAILED" => "Something went wrong while deleting your avatar.",
	"FEEDBACK_PASSWORD_RESET_TOKEN_FAIL" => "Could not write token to database.",
	"FEEDBACK_PASSWORD_RESET_TOKEN_MISSING" => "No password reset token.",
	"FEEDBACK_PASSWORD_RESET_MAIL_SENDING_ERROR" => "Password reset mail could not be sent due to: ",
	"FEEDBACK_PASSWORD_RESET_MAIL_SENDING_SUCCESSFUL" => "A password reset mail has been sent successfully.",
	"FEEDBACK_PASSWORD_RESET_LINK_EXPIRED" => "Your reset link has expired. Please use the reset link within one hour.",
	"FEEDBACK_PASSWORD_RESET_COMBINATION_DOES_NOT_EXIST" => "Username/Verification code combination does not exist.",
	"FEEDBACK_PASSWORD_RESET_LINK_VALID" => "Password reset validation link is valid. Please change the password now.",
	"FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL" => "Password successfully changed.",
	"FEEDBACK_PASSWORD_CHANGE_FAILED" => "Sorry, your password changing failed.",
	"FEEDBACK_PASSWORD_NEW_SAME_AS_CURRENT" => "New password is the same as the current password.",
	"FEEDBACK_PASSWORD_CURRENT_INCORRECT" => "Current password entered was incorrect.",
	"FEEDBACK_ACCOUNT_TYPE_CHANGE_SUCCESSFUL" => "Account type change successful",
	"FEEDBACK_ACCOUNT_TYPE_CHANGE_FAILED" => "Account type change failed",
	"FEEDBACK_NOTE_CREATION_FAILED" => "Note creation failed.",
	"FEEDBACK_NOTE_EDITING_FAILED" => "Note editing failed.",
	"FEEDBACK_NOTE_DELETION_FAILED" => "Note deletion failed.",
	"FEEDBACK_COOKIE_INVALID" => "Your remember-me-cookie is invalid.",
	"FEEDBACK_COOKIE_LOGIN_SUCCESSFUL" => "You were successfully logged in via the remember-me-cookie.",
  "FEEDBACK_CAR_CREATION_FAILED" => "Car creation failed.",
  "FEEDBACK_EVENT_CREATION_FAILED"  => "Event creation failed.",
  "FEEDBACK_EVENT_EDIT_FAILED"  => "Event edit failed.",
  "FEEDBACK_LANGUAGE_TEST"  => "This is in English.",
  "HEADER_MY_CARS"  => "My cars",
  "ADD_NEW_CAR"  => "Add a new car",
  "LOGIN_HERE" => "Login here",
  "LOGIN_USERNAME_OR_EMAIL" => "Username or email",
  "LOGIN_PASSWORD" => "Password",
  "LOGIN_REMEMBER_ME" => "Remember me for 2 weeks",
  "LOGIN_LOG_IN" => "Log in",
  "LOGIN_FORGOT_MY_PASSWORD" => "I forgot my password",
  "LOGIN_NO_ACCOUNT_YET" => "No account yet ?",
  "LOGIN_REGISTER_NEW_USR" => "Register",
  "REGISTER_NEW_ACCOUNT" => "Register a new account",
  "REGISTER_USERNAME" => "Username (letters/numbers, 2-64 chars)",
  "REGISTER_EMAIL" => "email address (a real address)",
  "REGISTER_PASSWORD" => "Password (6+ characters)",
  "REGISTER_REPEAT_PASSWORD" => "Repeat your password",
  "REGISTER_ENTER_CAPTCHA" => "Please enter above characters",
  "REGISTER_RELOAD_CAPTCHA" => "Reload Captcha",
  "REGISTER_REGISTER" => "Register",
  "HERO_BLURB" => "Everything that ever happened to your car",
  "HERO_WRITEUP" => " allows you to enter and store events associated with your car (oil change, belt replacement, brake job, that time you backed into a mailbox post, your restoration project accomplishments, etc.). Service providers (mechanics, body shops, spare parts vendors, etc.), upon your authorisation, can look at your data and see the history of your car.",
  "MENU_INDEX" => "Home",
  "MENU_MY_CARS" => "My cars",
  "MENU_LOGIN" => "Login",
  "MENU_REGISTER" => "Register",
  "MENU_MY_ACCOUNT" => "My Account",
  "MENU_LOGOUT" => "Logout",
  "MENU_ABOUT_CARAMNESIS" => "About CARAMNESIS",
  "REGISTRATION_WRITEUP"  => " is free. We will not send you any emails, except when it is necessary to confirm your identity (e.g. when registering or recovering forgotten password, etc.).",
  "PWCHANGE_REQUEST" => "Request a password reset",
  "PWCHANGE_ENTER_YOUR_DATA" => "Enter your username or email and you'll get a mail with instructions:",
  "PWCHANGE_SEND_IT_TO_ME" => "Send me a password-reset mail",
  "PWCHANGE_SET" => "Set new password",
  "PWCHANGE_NEW_PWD" => "New password (min. 6 characters)",
  "PWCHANGE_NEW_REPEAT" => "Repeat new password",
  "PWCHANGE_NEW_SUBMIT" => "Submit new password",
  "PWCHANGE_BACK_TO_LOGIN" => "Back to Login Page",
  "LOGIN_VERIFICATION" => "Verification",
  "LOGIN_BACK_TO_HOMEPAGE" => "Go back to home page",
  "NEWCAR_ADD" => "Add a new car",
  "SAVE" => "Save",
  "EDIT" => "Edit",
  "NEWCAR_FRIENDLY_NAME" => "Nickname",
  "NEWCAR_FRIENDLY_NAME_PLACEHOLDER" => "e.g. my rolling heap of junk #3",
  "NEWCAR_VIN" => "VIN",
  "NEWCAR_MAKE" => "Make",
  "NEWCAR_MODEL" => "Model",
  "NEWCAR_PLATES" => "License plates",
  "EVENT_CONTENT" => "Event content",
  "EVENT_TYPE" => "Event type",
  "EVENT_ODO" => "Event odo",
  "EVENT_DATE" => "Event date",
   
);
