######## README ##########

Original code: Gabe Mansur (gabe.mansur@gmail.com)
Updates and extensions: Brian Leonard (brianleonardconsulting@gmail.com)

Developed under the supervision of Ben Weidmann (benweidmann@g.harvard.edu) and Stephanie Taube (staube@hks.harvard.edu) 
Sponsored by Prof. David Deming and the Harvard Graduate School of Education

######## LOCAL SETUP ########

For Windows Users:

(if needed)
-Install Laragon to manage local DB and servers for testing and dev (https://laragon.org/download)
-Install Composer (http://getcomposer.org/installer)
-Install Git for Windows

-Open cmd prompt and navigate to C:\laragon\www
-Clone the app into a directory here (use command 'git clone https://github.com/bleonar5/Teamwork.git')
-Navigate to the newly created Teamwork directory
-Open the laragon app and "start all" 
-Click "database" in laragon (use 'root' and blank for pw to login) and create a new database by clicking New at the top of the far left column. Name it 'homestead'.
-Create a free Pusher account and keep the app_id, app_key, and app_secret_key they provide you (https://pusher.com/signup)
-Modify the env.example file in the main directory, inserting your own  credentials for the three 'Pusher...' variables.

(run these commands)
-'composer install'
-'php artisan key:generate'
-'php artisan config:clear'
-'php artisan config:cache'
-'php artisan migrate'
-'php artisan db:seed'
then 
-'php artisan serve'
(this will run indefinitely)

-open up a new cmd prompt window and navigate to C:\laragon\www\Teamwork again
-run command 'php artisan queue:work'
(this will also run indefinitely)

Now, you should be able to open localhost:8000 and test out the app

########## USAGE ##########

To login as an admin, go to localhost:8000 and use:
username: skillslab@hks.harvard.edu
pw: skillslab

As an admin, these will be the main URL endpoints you use:
(add these to the end of localhost:8000 or harvardskillslab.org to reach specific pages)
- '/admin-menu' will give you a menu with the option of navigating to either the Session Info (current session) or Historical Data (past sessions) page.
- '/admin-page' goes directly to Session Info
- '/historical-data' goes directly to Historical data

To run any of the task lists, we use specified login endpoints:
(crypto pilot -- including consent and front matter)
-/participant-login/crypto-pilot
(crypto pilot -- skips straight to waiting room)
-/participant-login/waiting-room
(memory task)
-/participant-login/group-memory
(individual pilot)
-/participant-login/individual-pilot

########## APP STRUCTURE ###########

URL endpoints defined at: 
		routes/web.php

Server-side php code that handles URL endpoints:
		app/Http/Controllers/

Models that define database tables: (for example User.php defines the Users table)
		app/

Events that allow the server to communicate live with the user's window:
		app/Events

Jobs that allow the server to schedule future events (used for scheduling subsessions):
		app/Jobs

HTML-like display templates, aka the different "pages" that we see (including page-specific javascript):
		resources/views/layouts/participants (including tasks subdirectory)

Generalized javascript and css:
		public/

