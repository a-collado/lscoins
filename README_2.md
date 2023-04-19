# LSCoins

Important note: this graded exercise must be done individually.

Crypto? What is that? It has been a new rising trend for the past couple of years. Cryptocurrency is a digital asset. It
comes from the fact that all of its transactions are highly encrypted. This makes the exchange highly secure. To enter
the world of crypto, we'd like to create a blockchain, which can be used for storing our LSCoin cryptocurrency
transaction records. But before going deeper into that, we'd need to be able to log in and sign up into the platform.

## Pre-requisites

To be able to create this web app, you are going to need a local environment suited with:

1. Web server (Nginx)
2. PHP 8
3. MySQL
4. Composer

You have to use the Docker `local-environment` set up that we have been using in class.

### Requirements

1. Use Slim as the underlying framework.
2. Create and configure services in the `dependencies.php` file. Examples of services are Controllers, Repositories, '
   view', 'flash', ...
3. Use Composer to manage all the dependencies of your application. There must be at least two dependencies.
4. Use Twig as the main template engine.
5. Use MySQL as the main database management system.
6. You MUST use the structures available in Object-Oriented Programming: Namespaces, Classes and Objects.

## Resources

### MySQL

Add your [schema.sql](./resources/schema.sql "Schema SQL") in `docker-entrypoint-initdb.d` folder to create the tables
in the MySQL database.

## Exercise

To complete the exercise, you will need to create three different pages:

1. Sign-up
2. Sign-in
3. Homepage

### Sign-up

This section describes the process of signing up a new user into the system.

| Endpoints | Method |
|-----------|--------|
| /sign-up  | GET    |
| /sign-up  | POST   |

When a user accesses the **/sign-up** endpoint you need to display the registration form. The information of the form
must be sent to the same endpoint using a **POST** method. The registration form must contain the following inputs:

* email - required . This must be a `text` field in HTML.
* password - required
* repeat password - required
* coins - optional . This must be a `text` field in HTML.

State explicitly the date format for birthday as the input field placeholder or next to the input field (e.g YYYY-MM-dd)
.

When a **POST** request is sent to the **/sign-up** endpoint, you must validate the information received from the form
and sign up the user only if all the validations have passed. For this and other sections, validations must be done in
the server, using PHP; client-side validation is insecure and is mostly done for UX reasons. The requirements for each
field are as follows:

* email: It must be a valid email address. Only emails from the domain @salle.url.edu are accepted. The email must be
  unique among all users of the application.
* password: It must not contain **less than** 7 characters. It must contain both upper and lower case letters. It must
  contain numbers. It must be stored using a hash algorithm.
* repeatPassword: It must be the same as password.
* coins: The minimum number of LsCoins to deposit on sign-up is 50 and the maximum is 30000. It must not contain any
  decimals (i.e. 65,89 LC).

If there is any error, you need to display the sign up form again. All the information entered by the user must be kept
and shown in the form (except for password fields) together with all the errors below the corresponding inputs.

Here are the error messages that you need to show respectively:

* Only emails from the domain @salle.url.edu are accepted.
* The email address is not valid.
* The password must contain at least 7 characters.
* The password must contain both upper and lower case letters and numbers.
* The number of LSCoins is not a valid number.
* Sorry, the number of LSCoins is either below or above the limits.
* Passwords do not match.

Once the user's account is created, the system will now allow the user to sign in with the newly created credentials.

### Sign-in

This section describes the process of logging into the system.

| Endpoints | Method |
|-----------|--------|
| /sign-in  | GET    |
| /sign-in  | POST   |

When a user accesses the **/sign-in** URL you need to display the sign-in form. The information of the form must be sent
to
the same endpoint using a POST method. The sign-in form must contain the following inputs:

* email
* password

When the application receives a POST request in the **/sign-in** endpoint, it must validate the information received
from
the form and if all the validations have passed, the system will try to log in the user. The validations of the inputs
must be exactly the same as in the registration.

If there is any error or if the user does not exist, you need to display the form again with all the information
provided by the user and display the corresponding error.

Here are the error messages that you need to show respectively:

* The password must contain at least 7 characters.
* The password must contain both upper and lower case letters and numbers.
* The email address is not valid.
* User with this email address does not exist.
* Your email and/or password are incorrect.

As you can observe, some of these messages are the same as in the Sign-up page. Think about how you structure your code
and make it reusable.

After logging in, the user will be redirected to the Homepage which is described in the next section.

### Homepage

The contents of this page will change depending whether the user is authenticated or not. If the user is logged in, this
page simply shows a "Hello \<username\>!". Otherwise, it should show "Hello stranger!".

The username is the first part of the email. For example, for "student@salle.url.edu", the username is "student".

### Profile, Change Password and Market

Let's imagine that the website has 3 other pages: Profile, Change Password and Market. The
corresponding endpoints are:

* /profile
* /profile/changePassword
* /market

Any user can access the Homepage, even unauthenticated ones. However, for the Profile, Change Password and Market pages,
the user must be authenticated (logged in). If any unauthenticated user tries to access these 3 pages, they will be
**redirected to the Sign-in page** and a **Flash message** should be shown. The specific message to be shown is "You
must be logged in to access the \<name\> page." where `name` can be "Profile", "Change Password" or "Market".

To implement this, you MUST use the following concepts:

- [Flash messages](https://www.slimframework.com/docs/v3/features/flash.html) (This is for v3 slim, we are using v4. See
  also the documentation available in the subject)
- [Middlewares](https://www.slimframework.com/docs/v4/concepts/middleware.html)
- [Route groups](https://www.slimframework.com/docs/v4/objects/routing.html#route-groups)

You can create twig files for the Profile, Change Password and Market pages, but they can contain any text you want.

## Tests

To check the validity of this exercise, we will be using [Cypress](https://www.cypress.io/). It is a Javascript
End-to-End Testing Framework. For the tests to work, we need to add custom attributes to HTML elements. The attributes
will follow the format:

```
data-cy=""
```

In the Sign-up page, you MUST add the following attributes:

```
<form data-cy="sign-up">
    <input data-cy="sign-up__email">
    <input data-cy="sign-up__password">
    <input data-cy="sign-up__repeatPassword">
    <input data-cy="sign-up__coins">
    <input data-cy="sign-up__btn">
    <span data-cy="sign-up__wrongEmail"></span>    // <span> can be a different element
    <span data-cy="sign-up__wrongPassword"></span> 
    <span data-cy="sign-up__wrongCoins"></span> 
</form>
```

As you can see, the values are different for each input, including the form itself. Your HTML can have other elements
and attributes, but take note that these `data-cy` attributes must exist for the tests to work.

You MUST add the attributes for the following pages:

In the Login page:

```
<form data-cy="sign-in">
    <input data-cy="sign-in__email">
    <input data-cy="sign-in__password">
    <input data-cy="sign-in__btn">
    <span data-cy="sign-in__wrongEmail"></span>    // <span> can be a different element
    <span data-cy="sign-in__wrongPassword"></span> 
</form>
```

Aside from this, the **flash message** in the Sign-in page should have the `data-cy="sign-in__message""` attribute as
well.

In the Homepage:

```
<h1 data-cy="home__welcomeMsg"></h1>
```

In the Profile page:

```
<h1 data-cy="profile">Here the user can see the profile information.</h1>
```

In the Change Password page:

```
<h1 data-cy="changePassword">Here the user can change passwords.</h1>
```

In the Market page:

```
<h1 data-cy="market">Here the user can access the cryptomarket.</h1>
```

### Considerations

1. The endpoints described in the previous sections MUST be used exactly as is. You cannot use **/register** or
   **/login**. These endpoints will make the tests fail.
2. If any of the given tests fails, then you will know which feature or validation does not work in your code.
3. Do not modify the tests. Any modification in the tests will surely make the tests fail during the grading of your
   deliverable.

## Delivery

### Format

You must upload a .zip file with the filename format `AC2_<your_login>.zip` containing all the code to the eStudy.
