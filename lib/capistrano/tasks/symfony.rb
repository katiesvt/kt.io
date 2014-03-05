namespace :symfony do

  namespace :cache do
    
    desc 'Clear cache and dump Assetic assets'
    task :clear do
      on roles(:web, :app), in: :parallel do |host|
        execute :php, "app/console", "cache:clear", "--env=#{stage}", "--no-debug"
        execute :php, "app/console", "assetic:dump", "--env=#{stage}", "--no-debug"
      end
    end

  end
end
