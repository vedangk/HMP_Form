# Dev Exercise: Custom Drupal Module - `person_info_form`

This repository contains a custom Drupal 9/10 module named `person_info_form`. It provides a styled user form for collecting personal information such as name, email, phone details, and favorite colors. The module includes a custom database table to store submissions and an admin page to view them.

It also includes:
- Responsive styling for desktop and mobile views
- Custom multiselect with chips and checkboxes using `Choices.js`
- Integration with the Drupal admin panel to list submissions

## Screenshots
- `Form-Desktop`: Full desktop view of the form
- `Form-Mobile`: Mobile responsive view
- `Filled-Form`: A sample form filled before submission
- `Form-Submit`: Confirmation view after successful form submission
- `Submissions Table`: View of the admin panel listing all submissions

---

## Requirements

- PHP 8.x+
- Composer
- Drupal 9 or 10
- XAMPP (or any local server with Apache/MySQL)

---

## Installation & Setup

1. **Clone the Repository**
   ```bash
   git clone https://github.com/<your-username>/Dev_Exercise.git
   ```

2. **Move Repository into Drupal**
   Place the folder inside your Drupal installation at:
   ```
   /web/modules/custom/person_info_form
   ```

3. **Enable the Module**
   In your Drupal site, go to:
   ```
   /admin/modules
   ```
   Find "Person Info Form" and enable it.

4. **Clear Cache**
   Visit:
   ```
   /admin/config/development/performance
   ```
   Click "Clear all caches".

---

## Using the Form

1. **Access the Form**
   ```
   /person-info
   ```
   Fill out the form and submit.

2. **Validation**
   - Phone must be 10 digits
   - At least two favorite colors must be selected

3. **View Confirmation**
   You’ll see a success message upon submission.

---

## View Submitted Data (Admin Panel)

A custom admin page is available to view form submissions saved in the database.

### Access the Admin Panel

1. Go to the following path in your site:
   ```
   /admin/person-info/submissions
   ```

2. You will see a table with:
   - First name
   - Last name
   - Email
   - Phone type and number
   - Favorite colors
   - Submission timestamp

3. This page is accessible to users with `access content` permission.

---

## Notes

- Ensure `libraries/choices` folder contains the minified Choices.js files.
- The form uses AJAX to reveal SMS consent only if "Mobile" phone type is selected.
- Fully responsive styling included in the `css` folder.

---