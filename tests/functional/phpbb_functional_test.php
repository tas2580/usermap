<?php

/**
 * @group functional
 */
class phpbb_functional_test extends \tas2580\usermap\tests\base\functional_test
{
	public function test_version_check()
	{
		// Log in to the ACP
		$this->login();
		$this->admin_login();
		$this->add_lang('acp/extensions');
		// Load the Pages extension details
		$crawler = self::request('GET', 'adm/index.php?i=acp_extensions&mode=main&action=details&ext_name=tas2580%2Fusermap&sid=' . $this->sid);
		// Assert extension is up to date
		$this->assertGreaterThan(0, $crawler->filter('.successbox')->count());
		$this->assertContains($this->lang('UP_TO_DATE', 'Usermap'), $crawler->text());
	}


	public function test_add_place()
	{
		$crawler = $this->request('GET', 'app.php/usermap/add?lon=7.291381835937491&lat=48.50838626913593');

		$form = $crawler->selectButton('submit')->form();
		$form->setValues(array(
			'message'		=> 'test message',
			'marker_type'	=> 1,
			'title'			=> 'Test Place'
		));
		$crawler = self::submit($form);

		$crawler = $this->request('GET', 'app.php/usermap/place/1');
        $this->assertGreaterThan(0, $crawler->filter('.content')->count());
	}

	public function test_add_place_comment()
	{
		$crawler = $this->request('GET', 'app.php/usermap/comment/add/1');

		$form = $crawler->selectButton('submit')->form();
		$form->setValues(array(
			'message'		=> 'Test comment',
			'title'			=> 'Test Place comment'
		));
		$crawler = self::submit($form);

		$crawler = $this->request('GET', 'app.php/usermap/place/1');
        $this->assertGreaterThan(1, $crawler->filter('.content')->count());
	}

}
