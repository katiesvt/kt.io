namespace :composer do

  desc 'Install Composer dependencies'
  task :install do
    on roles(:web, :app), in: :parallel do |host|
      execute :php, 'composer.phar', :install, "--no-dev", "--optimize-autoloader"
    end
end
