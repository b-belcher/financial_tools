# Financial Tools/Calculators

This repo contains my first site/app, and is intended to show the different iterations that the project has taken. I've re-written/re-designed the site as I add new features that aren't easily supported by the architecture at the time, but have preserved these iterations in different branches.

There are three branches currently, each of which represents a different stage of building/improving the app:
 - [simple_design](https://github.com/b-belcher/financial_tools/tree/simple_design)
 - [master](https://github.com/b-belcher/financial_tools)
 - [add_db_connection](https://github.com/b-belcher/financial_tools/tree/add_db_connection) (WIP)

### simple_design

This branch represents my initial efforts to build a site with some helpful financial calculators. Each tool exists as an independent PHP file (albeit with some shared nav resources in the /inc folder), and all of the form inputs, HTML rendering, and calculations are handled within the that single file.

### master

The most-polished version of the site. This separates out elements of the previous site into a MVC-inspired architecture pattern, and adds some functionality and a new calculator.

This design relies on query parameters to set the Controller and action and handle navigation/routing appropriately - I relied pretty heavily on [this guide](http://requiremind.com/a-most-simple-php-mvc-beginners-tutorial/) to get started, but intend to rework the routing to provide more flexibility (see To-do).

### add_db_connection

I desperately wanted to build some sort of DB interface into the site in order to better learn - this branch is a WIP that establishes a simple DB connection and allows users to save and load input values. Currently, this uses a universal table for these storage values, saving each set as a new entry and retrieving the last row each time the user loads saved data. This isn't viable for any sort of scale or public implementation, but is a working POC for storing and retrieving values from a DB.

# Tools

Each of the following tools/calculators is intended to help better understand the overall costs associated with owning a home. Many such calculators exist, but these are designed to consider some variables that many calculators ignore or oversimplify (PMI payments) as well as to provide a "big picture" context - weighing the overall out-of-pocket costs against the equity invested in the home (as well as the option of considering appreciation of the home value over time).
 
 * Simple Mortgage Calculator
    * This takes a limited number of input values (Purchase price and Loan details) and provides a  breakdown of how each month's payment is applied to the principal and/or interest.
 * Advanced Mortgage Calculator
    * Similar to above, but with a much wider variety of options. This allows users to add more details about the purchase and the overall costs, such as PMI/MIP, taxes, insurance, etc. More importantly, this provides the option to calculate the overall costs/savings of adding extra monthly payments - especially important when determining whether to consider the additional cost of PMI.
 * Refinance Calculator
    * This is designed to calculate the overall net cost of refinancing an existing mortgage loan. This also provides the option of adding additional monthly payments towards the existing loan, which may be more efficient than refinancing.
 * Mortgage vs. Loan Calculator
    * Perhaps the most specific calculator here, this weighs the net overall costs of contributing additional monthly payments towards an existing mortgage loan or a second (generic) loan (ie, a student loan). While this is normally a straightforward calculation based on the interest rate of each loan, the possible cost of mortgage interest PMI - and the savings once it no longer applies -  is a variable that  this calculator weighs.
 
# Goals

This site is intended to serve as an opportunity to conceptualize and execute an app from start-to-finish, and to provide the opportunity to learn about basic front-end design, back-end architecture and data handling, security, and database implementation.

# To-do

* Sanitize user-input fields universally to prevent any sort of injection attempt.
* Improve consistency of variable names throughout models.
* Rewrite routing so that the URL path names dictate the Controller and action, rather than relying on query parameters. ie, `.../financial-tools/refinance/calculate` instead of `.../financial-tools/?controller=refinance&action=calculate`.
* Add an (optional) login system, allowing users to save and retrieve their own input values.
* Add data visualization such a a line graph. This would be especially helpful in comparing net costs over time and net difference as a result of refinancing.
* Add help tips for input fields.
* Add validation for input fields for required values and formats.