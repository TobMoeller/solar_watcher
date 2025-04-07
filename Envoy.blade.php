@setup
    $host = env('DEPLOY_HOST');
    $user = env('DEPLOY_USER');
    $repo = 'git@github.com:'.env('DEPLOY_REPO').'.git';

    $dirs = [];

    $dirs[] = $base = '/home/'.$user.'/code';
    $dirs[] = $releases = $base.'/releases';
    $current = $base.'/current';

    $storage = $base.'/storage';
    $dirs[] = $app = $storage.'/app';
    $dirs[] = $logs = $storage.'/logs';

    $envFile = $base.'/.env';
    $install = $releases.'/'.now()->format('Ymd_His');

    $serverConnection = $user.'@'.$host;
@endsetup

@servers(['prod' => $serverConnection])

@task('deploy', ['on' => 'prod'])
    export NVM_DIR="$HOME/.nvm"
    [ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh"

    git clone --depth=1 {{ $repo }} {{ $install }}

    ln -nfs {{ $envFile }} {{ $install }}/.env

    rm -rf {{ $install }}/storage/app
    rm -rf {{ $install }}/storage/logs
    ln -nfs {{ $app }} {{ $install }}/storage/app
    ln -nfs {{ $logs }} {{ $install }}/storage/logs

    cd {{ $install }}
    composer install --no-dev --optimize-autoloader

    npm ci
    npm run build

    php artisan migrate --force
    php artisan optimize

    ln -nfs {{ $install }} {{ $current }}

    cd {{ $releases }}
    ls -dt */ | tail -n +6 | xargs -d "\n" rm -rf
@endtask

@task('setup-filesystem', ['on' => 'prod'])
    @foreach ($dirs as $dir)
        if [ ! -d "{{ $dir }}" ]; then
            mkdir -p {{ $dir }}
            echo "{{ $dir }} erstellt."
        else
            echo "{{ $dir }} existiert bereits."
        fi
    @endforeach
@endtask
