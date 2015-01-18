<?php

namespace Pfe\Bundle\MoocLoginBundle\MoocLogin;

use Goutte\Client;

class MoocLogin {

    private $username;
    private $password;
    private $ecampus;
    private $mooc;

    public function setConfig($config) {
        $this->username = $config['username'];
        $this->password = $config['password'];
        $this->ecampus = array('domain' => $config['ecampus']['domain'], 'url' => $config['ecampus']['url']);
        $this->mooc = array('domain' => $config['mooc']['domain'], 'url' => $config['mooc']['url'], 'base_url' => $config['mooc']['base_url'], 'user_idp' => $config['mooc']['user_idp']);
    }

    public function login(\Goutte\Client $client = null) {
        $client = empty($client) ? new Client() : $client;

        $this->loginEcampus($client);
        $this->loginMooc($client);

        return $client;
    }

    /**
     * Login the bot in Ecampus
     *
     * @return Goutte\Client
     */
    protected function loginEcampus(\Goutte\Client $client) {
        $crawler = $client->request('GET', $this->ecampus['url']);

        $client->getCookieJar()->updateFromResponse($client->getResponse(), $this->ecampus['domain']);

        if (sizeof($client->getCookieJar()->all()) > 0) {
            $cookieJar = $client->getCookieJar()->all()[0];
            $cookie = new \Symfony\Component\BrowserKit\Cookie($cookieJar->getName(), $cookieJar->getValue(), 'Session', "/", $this->ecampus['domain'], true, false);

            $client->getCookieJar()->clear();
            $client->getCookieJar()->set($cookie);
        }

        $form = $crawler->selectButton('SE CONNECTER')->form();
        $form['username'] = $this->username;
        $form['password'] = $this->password;

        // soumet le formulaire
        $client->submit($form);
        $client->getCookieJar()->updateFromResponse($client->getResponse(), $this->ecampus['domain']);

        return $client;
    }

    /**
     * Login the bot in MOOC
     *
     * @return Client
     */
    protected function loginMooc(\Goutte\Client $client) {
        $client->followRedirects();

        $crawler = $client->request('GET', $this->mooc['base_url']);
        $client->getCookieJar()->updateFromResponse($client->getResponse(), $this->mooc['domain']);

        $crawler = $client->request('GET', $this->mooc['url']);
        $client->getCookieJar()->updateFromResponse($client->getResponse(), $this->mooc['domain']);

        $form = $crawler->filter('button.btn')->eq(2)->form(); //html();

        $form['user_idp'] = $this->mooc['user_idp'];

        $crawler = $client->submit($form);
        $client->getCookieJar()->updateFromResponse($client->getResponse(), $this->mooc['domain']);

        $form = $client->getCrawler()->filter("form")->form();
        $crawler = $client->submit($form);
        $client->getCookieJar()->updateFromResponse($client->getResponse(), $this->mooc['domain']);

        return $client;
    }

}
