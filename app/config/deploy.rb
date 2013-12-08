# =============================================================================
# MULTISTAGE
# =============================================================================
set :stages,              %w{default testing}
set :default_stage,       "default"
set :stage_dir,           "app/config/stages"
require 'capistrano/ext/multistage'
set :ssh_options, {:forward_agent => true}
set   :application,   "mcsuite"
set(:deploy_to)    { "/home/#{user}/www/#{fetch :maxim_domain}" }
role(:web) { domain }
role(:app, :primary => true) { domain }
# =============================================================================
# REQUIRED VARIABLES
# =============================================================================
#set   :application,   "MCSuite"
#set(:deploy_to)    { "/home/#{user}/www/#{fetch :maxim_domain}" }
#set(:deploy_to) { "/home/#{user}/www/#{application}" }

set   :application,   "MCSuite"
#set   :deploy_to,     "/home/admin/www/mcsuite"
set   :domain,        "198.27.75.59"
# =============================================================================
# SCM
# =============================================================================
set   :scm,           :git
set   :repository,    "git@bitbucket.org:c4d3r/mcsuite-application-eyeofender.git"

# =============================================================================
# PATHS
# =============================================================================

role  :web,           domain
role  :app,           domain, :primary => true
set   :app_path,      "app"

# =============================================================================
# SSH OPTIONS
# =============================================================================
set   :use_sudo,    false
set   :user,        "admin"

# als er een kopie op de server moet behouden worden van de git (zo moet het niet telkens alles kopieren)
# set :deploy_via, :remote_cache

# =============================================================================
# SYMFONY OPTIONS
# =============================================================================
set   :shared_files,      ["app/config/parameters.yml", "src/Maxim/CMSBundle/Resources/config/settings.yml"]
set   :shared_children,   [app_path + "/logs", web_path + "/uploads", "vendor"]
set   :use_composer,      true
set   :update_vendors,    true
set   :assets_install,    true
set   :cache_warmup,      false
set   :copy_vendors,      true
set :dump_assetic_assets, true

# Controllers to clear
set :controllers_to_clear, ['app_dev.php']
# =============================================================================
# CAPISTRANO OPTIONS
# =============================================================================
set   :keep_releases, 3
before "symfony:assetic:dump", "symfony:cache:clear"
after "deploy:update", "deploy:cleanup"
logger.level = Logger::MAX_LEVEL

# =============================================================================
# TASKS
# =============================================================================

#permissions fix
after "deploy:update_code" do
  namespace :symfony do
    desc "--> Fixing permissions"
    run "cd #{latest_release} && find . -type f -exec chmod 644 {} \\;"
    run "cd #{latest_release} && find . -type d -exec chmod 755 {} \\;"
    puts "--> Permissions adjusted"
  end
end


# Custom(ised) tasks
#namespace :deploy do
# Apache needs to be restarted to make sure that the APC cache is cleared.
# This overwrites the :restart task in the parent config which is empty.
#  desc "Restart Httpd"
#  task :restart, :except => { :no_release => true }, :roles => :app do
#    run "service httpd restart"
#    puts "--> Httpd successfully restarted".green
#  end
#end

# MULTISTAGE EXTENSION
#set :stages,        %w(production testing mcspvp)
#set :default_stage, "staging"
#set :stage_dir,     "app/config"
#require 'capistrano/ext/multistage'