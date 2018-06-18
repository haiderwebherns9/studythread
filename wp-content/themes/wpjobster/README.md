# Jobster WordPress Theme

Jobster is the most advanced and feature rich WordPress theme for service marketplace.
Developed and maintained by the people who run a successful marketplace of their own.

## Changelog

### v5.1.0 - Feb 15th, 2018

Features
* Added Job Packages
* Added option for displaying last seen date on user profile page
* Added option for reCaptcha on login & register
* Added request custom offer button on user profile page
* Added email confirmation for withdrawal request

Improvements
* Added support for upcoming escrow payment options
* Added user rating with stars and count on single job page
* Added function_exists condition to the cards function (job thumbnails can now be completely replaced/customized)
* Added decimals support for custom offer price
* Added mandatory phone number option during registration
* Improved validation for job price
* Improved support for emoji
* Improved redirect from badge page to topup page if user balance is smaller than price
* Improved user online/offline status option

Bugfixes
* Fixed duplicate SMS notifications for admin
* Fixed font settings by updating Kirki
* Fixed sales dashboard issues with small amounts and dateranges
* Fixed duplicate reviews
* Fixed Facebook register issue
* Fixed jQuery issue for not logged in users
* Fixed search filters breaking thumbnails on some installations
* Fixed iOS 11 caret displacement on the login & register inputs
* Fixed order in queue if the payment is not complete
* Fixed payment gateway for custom extra report
* Fixed pending payment for job purchase report
* Fixed stripe amount on subscription page
* Fixed various translations
* Fixed list and grid views
* Fixed user balances pagination in admin

### v5.0.5 - Nov 28th, 2017

Features
* Added option for displaying other user level icons on thumbnails

Improvements
* Added new instructions fields for post new job and post new request request
* Calendar using Semantic UI for all of the old date inputs
* Improved custom offers modals performance
* Removed deleted jobs from recently bought section
* Removed pending jobs from my active jobs
* Updated Kirki plugin
* Updated ACF plugin

Bugfixes
* Fixed PayPal pending order issues
* Fixed new line issue for job instructions
* Fixed translations for calendar
* Fixed blacklisted phone prefixes filter
* Fixed uploader bug when wp-config.php is out of the installation folder
* Fixed rating metadata for jobs added from wp-admin
* Fixed MIME-types list which were breaking some downloads
* Fixed blank error when registering with wrong email
* Fixed search by location category inconsistencies
* Fixed color options for WP 4.7
* Fixed PHP warnings

### v5.0.4 - Nov 14th, 2017

Features
* Added option for email verification requirement

Improvements
* Improved header menu style
* Improved responsive sub-menu style
* Improved All Categories page style

Bugfixes
* Fixed auto-load posts
* Fixed theme icons when theme name is changed
* Fixed PHP short_open_tag
* Fixed user registered date
* Fixed customizer plugin required notification
* Fixed footer menu update from v4 to v5
* Fixed Options and Custom Fields pages
* Fixed small translation issues

### v5.0.3 - Nov 9th, 2017

Bugfixes
* Renamed wpjobster theme folder
* Fixed ACF on child theme
* Fixed fatal error on demo content import

### v5.0.2 - Nov 8th, 2017

Bugfixes
* Fixed jQuery undefined error

### v5.0.1 - Nov 8th, 2017

Improvements
* Added validation for subscription icon
* Added user status on search users page
* Added filters for serch users and single job pages

Bugfixes
* Fixed fatal error on database update
* Fixed Credits, COD and Bank Transfer payment gateway issues
* Fixed part of the Visual Composer frontend editor issues
* Fixed PNG file upload issues
* Fixed multilevel top menu style
* Fixed dropdown category on homepage search
* Fixed translations for user drop down menu
* Fixed wide logo overflowing for mobile devices
* Fixed icon size on levels page
* Fixed responsive menu issues for Android
* Fixed footer for Internet Explorer 11 and below
* Fixed homepage jobs display for Firefox

### v5.0.0 - Nov 1st, 2017

Features
* Rebuilt and optimized the complete front end with Semantic UI
* AJAX private messages and transaction page (no page refresh)
* Modals for various actions throughout the theme
* Transaction delivery countdown timer
* User menu replacing categories while on /my account/ pages
* Change package name option for subscription
* Search and various other filters for requests
* Grid/List switch for job listings
* Scroll on top button
* Allowed MIME-types settings
* Page assignments settings

Improvements
* Improved theme file structure
* Improved theme performance, especially frontend
* Improved admin settings structure
* Improved layout settings through wp customizer (more to come)
* Improved quantity input (can be directly edited, besides +/-)
* Links shared through messages are now clickable
* User profile page can now be customized
* Separated pending payment orders from active orders
* When changing email address, verification is required again
* Search users is ordering them by sales and rating
* Footer sticks to the bottom of the page
* With the account segregation plugin user search can be limited to sellers
* Disable update notifications if needed: define('WPJ_DISABLE_UPDATES', true);
* Update job slug and title after editing the job

Bugfixes
* Fixed private file download for Android app

### v4.1.7 - Aug 28th, 2017

Improvements
* Improved redirect after login
* Added max delivery time option for custom offer
* Added new error to prevent users from submitting subscription without selecting an option
* Added request custom offer button for buyers

Bugfixes
* Fixed various payment issues
* Fixed mandatory to upload pictures for jobs
* Fixed issues for job multiples
* Prevent not logged in users from accessing post new request page

### v4.1.6 - Aug 22th, 2017

Improvements
* Reorganized email and SMS settings
* Added hooks for withdrawals
* Subscriptions accept direct paymetns with 2Checkout and Authorize.net

Bugfixes
* Fixed PayPal pending order issues
* Fixed subscription fees feature
* Fixed submit review after error popup
* Fixed a few emails
* Fixed location radius search

### v4.1.4 - Jul 22th, 2017

Improvements
* Added instructions for 2Checkout payment gateway
* Added payment collected log for custom extras
* Included custom extras in pending incoming payments
* Removed pending incoming payments when clearing is instant
* Removed not delivered orders from pending incoming payments

Bugfixes
* Fixed response HTTP status for Ebanx payment gateway
* Fixed various payments log inconsistencies and currency bugs

### v4.1.3 - Jul 14th, 2017

Features
* WYSIWYG editor for job description
* Automatically grab profile picture from the social login

Improvements
* Added admin email about pending bank transfer payments
* Added translations for the payment methods
* Added the transaction ID on the payments page
* Added wp settings week starting day option to calendars
* Added separator option for CSV export
* Added company name to the live search
* Added hooks for custom payment buttons
* Allow decimals for featured job price
* Removed disabled emails from user email settings
* Removed duplicated job approval email
* Removed paused jobs from user profile

Bugfixes
* Fixed disabled levels for subscription upgrade
* Fixed private messages missing table issue
* Fixed favorite jobs missing table issue
* Fixed requests displaying the name instead of the username
* Fixed post new request tooltips issues
* Fixed google undefined JS error
* Fixed RTL half star rating issue

### v4.1.2 - Jul 5th, 2017

Features
* Lifetime subscriptions

Improvements
* Added active subscriptions list
* Added counters for unapproved jobs and requests
* Added delivery time range option
* Added company field option
* Added rejection reason for job attachments
* Added custom offer reviews to the job page
* Added configurable limits for extra fast and additional revision multiples
* Added filter for job purchase gateways buttons
* Added hooks for external SMS gateways
* Added hooks for gateways custom messages
* Added 0 delivery days in admin for instant jobs
* Added let's meet for requests
* Improved the live search ux
* Included fees and taxes on total spent
* Prevented multiple clicks on mark as delivered
* Prevented multiple clicks on feedback submit
* Prevented users from selecting earlier request end date
* Prevented users from using lower budget to range
* Removed job quantity when no multiples
* Removed processing fees when not applicable
* Removed the payment type prefix from PayPal order details

Bugfixes
* Fixed unclickable +/- for multiples
* Fixed various PHP errors
* Fixed subscription page links
* Fixed search user page translations
* Fixed social registration fields issues
* Fixed job preview issues
* Fixed long description display issues
* Fixed inconsistency on request owner buttons
* Fixed country flag for user search
* Fixed search shortcode style for Safari
* Fixed user reviews pagination in admin

### v4.1.1 - May 24th, 2017

Features
* Search for requests
* New shortcode: [advanced-search]
* Allow users to change their email address

Improvements
* Removed a few plugins and included their functionality in the theme
* Removed deprecated options
* Removed the current job from other jobs by
* Improved payment status update when returning from PayPal
* Improved location search radius calculation
* Improved IP location detection
* Added per level enable/disable options for extra fast and additional revision
* Added more labels for PayPal gateway settings
* Added total spent on the shopping page
* Added subcategories in alphabetical order
* Added instructions for post new request page
* Added the delivery time for each extra on job page
* Added admin notification when a job or request was edited
* Added request title generated from description if empty
* Added more hooks through the theme

Bugfixes
* Fixed Stripe duplicate payment when hitting the back button
* Fixed user type field position for account segregation
* Fixed expected delivery with extra fast
* Fixed file uploader issues for profile image
* Fixed default country flag issues
* Fixed private messages special characters
* Fixed report job title special characters
* Fixed custom offer title in transactions
* Fixed my ratings sender name
* Fixed subscriptions transaction log issues
* Fixed empty job preview container
* Fixed javascript cache issues
* Fixed timezone in admin settings
* Fixed cover image style for blog and news
* Fixed some number formatting issues
* Fixed date format for sales report
* Fixed sales report translations
* Fixed small CSS and responsive issues
* Fixed small RTL issues

### v4.1.0 - Apr 25th, 2017

Features
* Sales Reports for admin with CSV export
* Extra fast delivery
* Additional revision
* User portfolio
* User page WYSIWYG editor

Improvements
* Live notifications performance improvements
* Subscriptions accept direct paymetns with PayPal and Stripe
* Stripe checkout multuple languages
* Stripe save credit card info
* Default user country for register
* Default user timezone by country
* Added custom offer input limits
* Unique user phone numbers
* Jobs need at least 3 ratings in order to display stars
* Option to lock user to my account until email verified

Bugfixes
* Fixed https font issues
* Fixed https issues for thumbnails uploaded during http
* Fixed profile picture ratio in some cases
* Fixed emails for requests issues
* Removed unused YouTube fields from admin
* Fixed small translation issues
* Fixed small CSS and RTL issues
* Fixed country detection issues for some servers
* Fixed break rows after 80 chars on emails
* Fixed sort by rating
* Fixed PayPal checkout text encoding

### v4.0.5 - Mar 20th, 2017

Improvements
* Order my account jobs by date instead of id

Bugfixes
* Fixed PHP7 incompatibility
* Fixed layout on transaction page when viewed by an admin

### v4.0.4 - Mar 18th, 2017

Features
* Custom extras
* Job preview (public downloads)
* Dedicated post new request page

Improvements
* Added the hooks to the transaction page for the invoices plugin
* Added load more to new pages and improved it on the other pages
* Subscription user type upgrade feature (works with account segregation)
* Subscription levels can be disabled
* Made order id obfuscator function pluggable
* Merged level settings into user levels admin page
* Made timezone select labels translatable
* Added new error to prevent users from posting wrong youtube urls
* Styled the social login buttons for all of the networks
* Removed the 0 processing fee label if it is disabled

Bugfixes
* Fixed random jobs displaying duplicated entries on load more
* Fixed lock to my account until phone number verified
* Fixed job name not updated when paying with Stripe
* Fixed hash issues with 2Checkout
* Fixed missing qTranslate language switchers
* Fixed HTML encoding issues with AIO Support Center emails
* Fixed the IP detection function
* Fixed seveal PHP notices and warnings
* Fixed a few translation issues
* Fixed small CSS issues

### v4.0.3 - Feb 20th, 2017

Features
* User email settings
* Jobster menu to admin bar
* Prefix selector for phone numbers

Improvements
* Added necessary hooks for jobster affiliate plugin
* Phone number option for static register page
* Centralized site fees options
* Filter job and user profile descriptions by blacklisted words
* Added tabs to my account page
* Added option for automatically cancelling pending orders
* Improved backend options
* Order posted jobs by date

Bugfixes
* Fixed duplicated posts when random is enabled
* Fixed upload background button on user profile page
* Fixed missing thumbnails on the search page
* Fixed reviews not displaying sometimes
* Payment gateway fixes
* Responsive style fixes
* Translations fixes
* RTL style fixes

### v4.0.2 - Feb 2nd, 2017

Features
* Report job feature
* Display job instant delivery files for admin

Improvements
* Search users page initial query, load more, style, prioritize by sales
* Added pagination to several jobster admin pages
* Updated mark payment completed emails

Bugfixes
* Fixed missing initial transaction message
* Fixed submit feedback issues for non-latin languages
* Fixed live tax and fees calculation for buy buttons and total
* Fixed secure downloads for instant job
* Fixed register redirection
* Fixed Twilio SMS gateway errors
* Fixed delete buttons from admin private messages
* Fixed notifications page checkboxes style
* Fixed instant delivery input style
* Fixed revolution slider buttons style

### v4.0.1 - Jan 25th, 2017

Features
* Added live search suggestions, including user search
* Added user editable timezones
* Added file uploader option for requests
* Added mark notifications as read
* Added description on category pages

Improvements
* Allow other file types on post/edit job pages
* Added 'wpjobster_run_on_transaction_page' hook to transaction page
* Added total on pay for featured page
* Added option for need to have at least one posted job limit
* Added options for displaying budget and max delivery days on requests
* Moved options for requests on a different tab

Bugfixes
* Fixed transaction page broken after seller's feedback
* Fixed translations for Online/Offline
* Fixed active tab in admin transactions
* Fixed receiver column for admin transaction messages
* Fixed styling issues on thumbnails for long job titles
* Fixed footer styling on tag page
* Fixed an issue when max number of pictures option was empty
* Fixed several PHP notices and warnings
* Hidden buyer process button for bank transfer transactions

### v4.0.0 - Jan 17th, 2017

Features
* Gateways API v2
* Any gateway works now with top up and featured jobs
* Allow admin to see any transaction page
* Search for users

Improvements
* Added PayPal loader when redirected to the site
* Added PayPal IPN log for debugging
* Removed pending and failed orders from the active tab
* Removed notifications for pending orders
* Make it clear for seller that the buyer has chosen to pay cash on delivery
* Improved admin orders table labels
* Improved WP Better Emails compatibility
* Limited recent bought to jobs
* Updated empty email templates

Bugfixes
* Fixed total price on transaction page for COD
* Fixed reset password issues
* Fixed long usernames, titles and categories
* Fixed feedback message breaking after some special characters
* Fixed duplicated emails about job purchases
* Fixed displayed currency when clicking load more
* Fixed small issues with vacation mode
* Fixed admin quick edit job

### v3.9.4 - Dec 29th, 2016

Features
* Vacation mode

Improvements
* Newlines on private messages and transaction messages
* Included empty option to category select inputs
* Allowed empty job price and consider it zero
* Relabeled column headers in admin orders table
* Changed the link for post new request on home logged-in
* Phone number filter function can be redeclared in child theme
* Prepared the theme for the upcoming invoices plugin

Bugfixes
* Fixed Stripe payments bug for admin account
* Fixed Stripe failed payment redirect
* Fixed sending emails and updating status when editing job with quick edit
* Fixed price position when using dropdown values on post new job
* Fixed headings for the transaction messages table in admin
* Fixed category subcategory sequence on job page for RTL
* Fixed job_name shortcode for SMS templates
* Fixed characters counter initialization when loading the edit job page
* Fixed email notification for new purchases when admin marks transaction as paid
* Fixed wrong subcategories list displayed on edit job page
* Fixed title tag repeated with Yoast SEO plugin
* Fixed featured job label displayed twice on thumbnail

### v3.9.3 - Dec 12th, 2016

Features
* New shortcode displaying jobs from a certain category: [list_jobs]

Improvements
* Created post new request page and added it in the user menu
* Removed empty thumbnails from my requests page
* Disabled pending purchase emails by default

Bugfixes
* Fixed PayPal pending order issues
* Fixed expired license issues
* Fixed requests shortcodes
* Fixed PHP errors

### v3.9.2 - Nov 28th, 2016

Bugfixes
* Fixed compatibility issues with PHP older than 5.5
* Fixed custom offers purchase links
* Fixed translations for free jobs

### v3.9.1 - Nov 24th, 2016

Improvements
* Improved the multiple checkbox style
* Improved the post/edit job pages for easier customization through child themes
* Added default text for post/edit job pages tooltips
* Removed deprecated code and fixed some HTML errors
* Added unique IDs on the gateway links for easier targeting through CSS
* Included default translations: ar, da, de, es, fr, pt, pt_BR, sk

Bugfixes
* Fixed custom offers purchase links
* Fixed confirm password bug on Safari
* Fixed logo position for the login/register pages

### v3.9.0 - Nov 9th, 2016

Features
* Job multiples (or quantities)
* Live notifications (without page refresh) for messages and orders
* Budget and expected delivery fields for the requests

Improvements
* Reorganized user dropdown menu
* Replaced the old dynamic thumbnail function with a new, performance oriented one
* Improved Stripe checkout page on mobile devices
* Improved Dropzone image uploader style
* Added lazy loading to the job page slider for better performance
* Added support for buyer/seller account segregation through an external plugin

Bugfixes
* Fixed login required bug on category and some other pages
* Fixed user profile page meta data for social sharing with image
* Fixed user avatar and several other image uploads when Amazon S3 offload is active
* Fixed the issues with Emoji characters on regular UTF8 tables

### v3.8.2 - Oct 14th, 2016

Features
* Integrated support for storing files and media on Amazon S3
* Secure download links for private files shared on transactions or messages
* Option to redirect to a custom page after registration

Improvements
* Better qTranslate-X compatibility
* Responsive improvements

Bugfixes
* Fixed the license activation on multisite networks
* Fixed the issue when you had to click activate license twice
* Hidden the import demo content notice for old installations and made it dismissible

### v3.8.1 - Oct 6th, 2016

Features
* Automatic setup/installation package
* Demo content importer for new installations

Improvements
* Updated the default option for min price with 0 instead of empty
* Display user phone number in wp-admin users
* RTL for the user stats charts
* Rewrote the code behind the shortcodes for better Visual Composer compatibility
* Changed labels for default gateway success/failure pages to avoid confusion
* Responsive fixes
* Proper enqueue of the base font for better performance

Bugfixes
* Fixed Free label visual bug for prices over 1k with thousands separators
* Fixed tooltip for dropdown price on the post new job page
* Fixed HTML emails if beter emails plugin not enabled
* Fixed tax/fees issues with pending orders
* Fixed search button style on iPad
* Fixed login/register text overflow when social login is not enabled
* Fixed category dropdowns and extras issues on some installations
* Fixed levels page content not displaying bug
* Fixed display of large category names on job & search pages
* Fixed WP & PHP Notices regarding deprecated functions

### v3.8.0 - Sep 19th, 2016

Features
* Automatic theme updates
* Free jobs

Improvements
* Submit buttons style in Jobster settings

Bugfixes
* Fixed feedback response from seller not working
* Fixed custom offers not being stored

### v3.7.2 - Sep 8th, 2016

Bugfixes
* DropzoneJS Cover issues

Improvements
* Updated .pot file for translations

### v3.7.1 - Sep 2nd, 2016

Features
* Bank Transfer payment gateway
* Admin can mark the payment status as completed

Improvements
* Display payment gateway on the admin orders table

Bugfixes
* Fixed refunds after cancellation
* Fixed notifications read mark

### v3.7.0 - Aug 28th, 2016

Features
* Global translations folder compatibility
* New default file uploader for job images: DropzoneJS
* Requests can now be managed by users on my-requests page
* Email notifications for requests
* User profile picture in admin side

Improvements
* Requests have now single pages displaying the offers
* Old /request page can now be created using shortcodes
* Pending orders can now be processed or cancelled
* Display payment status on the admin orders table
* Cover image can now be managed by admin
* Improved static Login & Register pages (wp-login.php)
* Job page and user profile page social sharing snippet with correct thumbnail
* Removed deprecated theme options

Bugfixes
* Captcha by BestWebSoft v4.2.4  compatibility fix
* Several PHP notices and warnings fixed
* Messages page compatibility with PHP7
* Braintree small issues fixed
* COD payment gateway flow fixed
* Fixed fatal error on theme activation or db update for some servers
* RTL style fixes
* Fixed custom color for slider register button
* Strings


### v3.6.2 - Jul 27th, 2016

Improvements
* Format Numbers for Tax & Fees
* Transaction Status Label
* PayPal Gateway response
* Stripe Gateway response
* Advanced Search Shortcode Responsive
* Code cleanup

Bugfixes
* Fixed Tax & Fees for the new gateways methods
* Fixed credits payment status and flow
* Fixed redirect to transaction page after payment
* Pending status when saving as draft from admin
* Processing Fees < 1 not appearing on dropdown
* Extras on the transaction page

### v3.6.1 - Jul 20th, 2016

Bugfixes
* JS not working on some mobile devices

### v3.6.0 - Jul 16th, 2016

Features
* Payment Gateway Plugin API
* Migration from ACF4 to ACF5
* Shortcode for Search bar on the slider [advanced-search-slider]
* Debug mode: define('WP_JOBSTER_DEBUG', true); (NOT recommended in production!)
* Added pagination to the admin transactions page

Bugfixes
* Fixed PHP and WP deprecated functions
* Improved compatibility with PHP7
* Fixed some SQL errors
* Removed old Jobster deprecated functions
* RTL style fixes
* Fixed some inconsistencies in the admin settings
* Fixed some inconsistencies in the default email templates text
* Braintree gateway fixes and default table creation
* PerfectMoney gateway currency issues fix
* Theme main color works now on checkboxes, select iputs and several other elements
* Replaced theme color with green for success messages
* Remove select placeholders from options list
* User profile page works now for usernames with spaces
* Fixed the scroll jump when the header gets sticky on top
* Fixed missing job information on the transactions admin menu

### v3.5.0 - May 17th, 2016

Features
* Visual Composer integration
* Charts for advanced user stats
* Braintree Gateway
* Cash on delivery
* Login with the email
* SMS notifications default english templates

Bugfixes
* Feedback response notification fix
* Countries and languages from edit account page translatable
* Fixed blank logout page caused by latest WP update
* Fixed translation issues with the Ajax Login plugin + Loco Translate

### v3.2.0 - April 27th, 2016

Features
* All notifications page
* Old notifications improvements
* User online/offline status option
* Percentage option for processing fees
* The request input is refilled automatically after login
* Improved gateway functions
* INIPay Gateway - BETA
* Blockchain Gateway
* PerfectMoney Gateway
* Webpay Gateway

Bugfixes
* HTTPS for YouTube thumbnails
* Category search filter bug
* Character limits are working now with 0
* Featured job dates bugs
* Processing fee and tax included in total in shopping/sales pages
* PHP Warnings/Notices fixes
* Translation fixes
* RTL styling fixes

### v3.1.7-pre - April 7th, 2016

Features
* Display map option for the end user
* Google Maps API Key option
* Numeric fields (prices) can also use decimals
* Use the excerpt for news loop

Bugfixes
* Include paths updated in order to work with child themes
* Long titles breaking the responsive layout
* Instructions displaying on all the extra inputs at once
* Remove lazy loading icon behind transparent thumbnails
* Random jobs not working when featured jobs was disabled
* Removed potential error when automatically disabling a plugin
* Fixed PayUMoney variable typo

### v3.1.6 – March 24th, 2016

Features
* Tax is now country-based
* Responsive menu works now with CSS3 transitions for improved performance

Bugfixes
* Fixed the price position on the thumbnails
* Fixed the bug where orders couldn't be inserted on databases with custom prefixes
* Fixed side menu functionality and style for mobile devices
* Fixed checkboxes display on mobile devices
* Fixed job images display on mobile devices
* Fixed feedback response input not displaying on the transaction page
* Fixed the bug where admin couldn't delete job images
* Recently viewed jobs are now per user
* Fixed some issues in Firefox related to long titles and descriptions
* Fixed job thumbnails size on mobile devices
* Fixed input focus styles and custom colors
* Fixed PayUMoney redirect bug

### v3.1.5 – March 18th, 2016

Features
* Updated the blog archive style and implemented a load more button
* Updated default page template and added a new widget
* Updated the colour of the links on the payments page, because clients reported they were not obvious enough
* Updated the job page to display “instant” instead of “1 day” for instant jobs
* Added a new icon to the instant jobs thumbnail
* Updated all the numeric inputs in order to accept only numbers
* Updated the youtube video in order to not show any related videos, annotations or info
* Implemented Lazy load thumbnails option
* Updated login/register plugin in order to autofocus the first input when popping up

Bugfixes
* Fixed the bug where the news archive was not working
* Fixed the bug regarding the user level badge on the user profile page
* Fixed the bug where the user profile picture was wrong on the job feedback section
* Updated the style for the subscription icons
* Fixed the bug on the advanced search, where delivery was set by default to 0 instead of 30
* Updated the url encoding for the links sent in the emails, to prevent long ugly links for non-standard charsets
* Fixed translation for the “Let’s Meet” label on the job thumbnails
* Changed shipping price string from uppercase to regular caps
* Updated the styling of the checkboxes and prices on the extra services lists
* Fixed the bug where you could save a shipping price containing strings other than numbers only
* Fixed the bug where you couldn’t save tax with value “0”, even if it was disabled
* Fixed the bug where the YouTube video was not displaying when there was no image uploaded for the job
* Fixed the bug where the YouTube video was not in center of it’s container on the responsive version of the site
* Other RTL and general style fixes

### v3.1.0 - March 4th, 2016

Features
* Implemented ability to define Sales Tax percentage (%) which will be charged to buyers on each transaction
* Extended “Request Service” function with date, location and Google maps (Let’s meet)
* Implemented Google Maps for Jobs with specified locations
* Added the News post type in functions, disabled GD Post Types
* Rearranged options in General Settings
* Terms of Service can now be displayed in a separate box (container which can be scrolled) within a page

Bugfixes
* CSS Fixes
* 404 Page responsive
* Private Message not lost anymore if there is any error
* Phone verification errors
* Subscription Icon URL fixed
* Transaction logs currency and translation fixes
* Fixed several issues on the image uploader

### v3.0.0 - February 1st, 2016

* Subscriptions
* Top Up Account Balance
* Processing Fee for Buyers
* Load More News
* Enable/Disable News Box

Bugfixes
* CSS Fixes
* RTL Fixes
* Responsive Fixes
* Transactions History Translation Fixes
* Decimals Option not used on some values

### v2.8.2 - January 22nd, 2016

Bugfixes
* My Account Stats Bar
* Featured Dates
* Gateways Fixes
* File Uploads Fixes
* Translation Fixes
* CSS Fixes
* RTL Fixes

### v2.8.0 - December 22nd, 2015

* Statistics Bar regarding payments
* Redesigned Logged in Home Page
* Redesigned Job Display Grid
* Admin options for colors
* Admin options for level icons

Bugfixes
* Payza Live BUG Fix
* Fixed File Uploader Conflict with other plugins
* Fixed instances where user avatars were not being displayed on feedback sections
* Search Radius fixed (query for radius, not just coordinates)
* Homepage broken by missing featured categories
* Admin Ajax links on HTTPS
* Unsafe Scripts Admin
* Profile Info Page Save Bugs
* Rating response special characters

### v2.7.1 - November 13th, 2015

* PayPal withdrawal testing and improvements

Bugfixes
* RTL Fixes and better CSS organization
* CSS Fixes for Easy Social Share 3

### v2.7.0 - November 12th, 2015

* Database Update notice in admin
* All the Payment Gateways updated and tested
* Payumoney Payment Gateway Implemented
* Increased number of extras to 10 with options to set max for each user level
* All the feedback boxes are now using AJAX to load more feedback entries
* Sellers can respond to the Buyer's feedback
* Feedback box displayed on the User Profile page
* Improved style of the User Profile page
* Character Limits Options for most of the textareas through the site
* Improvements for the instant delivery file functionality
* Allow decimals in job prices and extras
* Option for thumbnails with video icon
* Option for the Audio file size
* Option to display requests on the homepage
* Pagination to a few admin pages like orders or reviews
* Button to pay with the account balance instead of automatically charge
* Exclude post types which should not be translated

Bugfixes
* Payza bug fix for when the buyer is closing the payment window before being redirected to the site
* Updated Stripe gateway for zero-decimal currencies
* Fixed a bug caused by Yoast SEO which prevented displaying error messages
* Messages not sent when multiple pages were open
* Admin menu save not redirecting to correct tab
* Blurry thumbnails on the file uploader
* Delete the temporary files after complete uploading
* Thumbnail image size increased in order to display better on mobile devices
* Register button not working when the social login buttons were not displayed
* Several other strings, translations and CSS fixes

### v2.6.2 – October 9th, 2015

* Added loading image to register button when registration is in process. Also disabled the Register button when process is going on.

### v2.6.1 – October 2nd, 2015

* Ability to force users to accept Terms & Conditions (checkbox) before posting a new service/job
* Requests are now being displayed based on the category they were submitted to
* Uploader Style Changes & Bugfixes

### v2.6.1 – October 1st, 2015

* Implemented new file uploader which can handle multiple images
* Ability for the users to upload Job cover photo
* Option to lock users on ‘my account’ page until phone number is verified
* Revolution Slider overlay and buttons fix
* Characters counter fix for newlines
* Wrap edit job page in a function for use in childthemes
* Include ACF in theme files
* Location radius – users can filter jobs within xx amount of miles/kilometers from their location
* Gateways security & bug fixes

### v2.5.8 – September 23rd, 2015

* Fixed textdomain translation issue
* Delivery Time bugfix related to Instant Delivery function

### v2.5.7 – September 22nd, 2015

* Minor bugfixes on the ‘post new job’ page
* Instant delivery feature implemented

### v2.5.6 – September 12th, 2015

* ALT Tags on stars images
* Fixed bug when counting non-standard characters

### v2.5.4 – September 5th, 2015

* Audio Files Support implemented – users can now upload audio files on the job/service pages
* Implemented ability to enable/define Tags for each job
* Your users can now send out physical products as well as charge for shipping.

### v2.5.2 – July 30th, 2015

* SMS Number Confirmation – ability to enable mobile number verification during registration process – based on the Twilio SMS Gateway
* PayPal semi-automatic withdrawal – select and process all of the withdrawal requests at once
* Auto-scroll (auto-load) feature for pages that display job/service listings.

### v2.5.0 – July 19th, 2015

* SMS Notifications – enable SMS notifications for all of your users. There will be no more missed offer requests, private messages, job deliveries, etc.  Based on the Twilio SMS Gateway
* Implemented clearing period (starts from the moment transaction is marked as completed) before the funds become available for withdrawal – Security feature to protect marketplace owners from chargebacks.
* Several Bugfixes (CSS, RTL, Translations)

### v2.4.8 – June 29th, 2015

* Jobs Order on Homepage: Ascending, Descending, Random
* Several PHP Bugfixes
* Plugin compatibility updates
* Ability to translate email notifications to be sent to users in the appropriate language.

### v2.4.4 – June 2nd, 2015

* Support all currencies from OpenExchangeRates
* Email confirmation – each new users is prompted to confirm their email address. Once the email address is confirmed it is displayed on their profile “email confirmed” along with the icon.
* IP geo-location to display content to users in the appropriate language.

### v2.4.3 – May 15th, 2015

* Instant job clear when clearing period set to “0”
* UI improvements and bugfixes
* Date internationalization through the theme – you can now select to display the date based on your geographic location and/or target market
* Ability to leave comments to sellers when approving jobs as to reasons why the service has been denied. Especially useful when you would like to provide pointers to quality sellers.

### v2.4.1 – May 4th, 2015

* Featured Jobs – implemented ability for users to pay for a featured spot on the home/category/subcategory pages. Ability to set the price and time interval from the admin section
* Responsive functionality added
* Implemented several new Payment Gateways
* Implemented filter for email addresses and URL’s in the messaging system as well as the transaction pages. Text for the warning message can be defined in the admin section. Additionally, you can create your own custom trigger words along with the warning messages.
