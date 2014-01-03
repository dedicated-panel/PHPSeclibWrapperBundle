PHPSeclib Wrapper Bundle
====
This bundle contains useful methods (connection managing, easy key storing, packet installed verification ...) on top of PHPSeclib library (pure php ssh/sftp client).

You can easily connect to ssh/sftp servers and do some basic operations (supports os-specific "basic" operations) via the API (upload, download, mkdir, touch, chmod, ...). You can also access directly the phpseclib API.
The wrapper provide logging functionnality on base method (exec, upload, download, touch, mkdir, ...).

You have also a base Server class provided by the bundle.

    $server = new Dedipanel\Server\Server();
    $server
        ->setHostname('localhost')
        ->setPort(22)
        ->setUser('test')
        ->setPassword('test)
    ;

The bundle provide a connection manager, allowing to use the same connection at different points :

    $logger  = new Psr\Log\NullLogger(); // logger used to log ssh/sftp interactions.
    $manager = new Dedipanel\ConnectionManager($logger);
    
    $connection   = $manager->getConnectionFromServer($server);
    $connectionId = $manager->getConnectionId($connection);

The bundle provide easy way to generate public/private key (see KeyHelper) and to store it (see FileKeyStore).
By default, these keys are stored and retrieved to/from files, but you can implements you own KeyStore strategy.

    $keyStore     = new Dedipanel\KeyStore\FileKeyStore(__DIR__ . '/keys/');
    $keyHelper    = new Dedipanel\KeyHelper($keyStore);
    $privateKeyId = uniqid(true); 
    $publicKey    = $keyHelper->createKey($privateKeyId);

You can also use the KeyHelper for directly uploading the public key to the server :

    $keyHelper->createKey($privateKeyId, $connection);
