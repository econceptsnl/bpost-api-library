<?php

require_once 'config.php';
require_once '../bpost.php';

require_once 'PHPUnit/Framework/TestCase.php';

/**
 * test case.
 */
class bPostTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var bPost
	 */
	private $bpost;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp()
	{
		parent::setUp();
		$this->bpost = new bPost(ACCOUNT_ID, PASSPHRASE);
	}

	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown()
	{
		$this->bpost = null;
		parent::tearDown();
	}

	/**
	 * Tests bPost->getTimeOut()
	 */
	public function testGetTimeOut()
	{
		$this->bpost->setTimeOut(5);
		$this->assertEquals(5, $this->bpost->getTimeOut());
	}

	/**
	 * Tests bPost->getUserAgent()
	 */
	public function testGetUserAgent()
	{
		$this->bpost->setUserAgent('testing/1.0.0');
		$this->assertEquals('PHP bPost/' . bPost::VERSION . ' testing/1.0.0', $this->bpost->getUserAgent());
	}

	/**
	 * Tests bpost->createOrReplaceOrder
	 */
	public function testCreateOrReplaceOrder()
	{
		$orderId = time();

		$customer = new bPostCustomer('Tijs', 'Verkoyen');
		$customer->setDeliveryAddress(new bPostAddress('Kerkstraat', '108', '9050', 'Gentbrugge'));

		$order = new bPostOrder($orderId, 'OPEN');

		$order->setStatus('OPEN');
		$order->setCostCenter('CostCenter1');
		$order->addOrderLine('Item 1', 10);
		$order->addOrderLine('Item 2', 20);
		$order->setCustomer($customer);
		$order->setDeliveryMethod(new bPostDeliveryMethodAtHome());
		$order->setTotal(100);

		$var = $this->bpost->createOrReplaceOrder($order);

		$this->assertTrue($var);
	}

	/**
	 * Tests bpost->fetchOrder
	 */
	public function testFetchOrder()
	{
		$orderId = time();

		$customer = new bPostCustomer('Tijs', 'Verkoyen');
		$customer->setDeliveryAddress(new bPostAddress('Kerkstraat', '108', '9050', 'Gentbrugge'));

		$order = new bPostOrder($orderId, 'OPEN');

		$order->setStatus('OPEN');
		$order->setCostCenter('CostCenter1');
		$order->addOrderLine('Item 1', 10);
		$order->addOrderLine('Item 2', 20);
		$order->setCustomer($customer);
		$order->setDeliveryMethod(new bPostDeliveryMethodAtHome());
		$order->setTotal(100);

		$this->bpost->createOrReplaceOrder($order);

		$var = $this->bpost->fetchOrder($orderId);

		$this->assertInstanceOf('bPostOrder', $var);
		$this->assertEquals($orderId, $var->getReference());
	}

	/**
	 * Tests bpost->modifyOrderStatus
	 */
	public function testModifyOrderStatus()
	{
		$orderId = time();

		$customer = new bPostCustomer('Tijs', 'Verkoyen');
		$customer->setDeliveryAddress(new bPostAddress('Kerkstraat', '108', '9050', 'Gentbrugge'));

		$order = new bPostOrder($orderId, 'OPEN');

		$order->setStatus('OPEN');
		$order->setCostCenter('CostCenter1');
		$order->addOrderLine('Item 1', 10);
		$order->addOrderLine('Item 2', 20);
		$order->setCustomer($customer);
		$order->setDeliveryMethod(new bPostDeliveryMethodAtHome());
		$order->setTotal(100);

		$this->bpost->createOrReplaceOrder($order);

		$var = $this->bpost->modifyOrderStatus($orderId, 'CANCELLED');

		$this->assertTrue($var);
	}


	/**
	 * Tests bpost->createNationalLabel
	 */
	public function testCreateNationalLabel()
	{
		$orderId = time();

		$customer = new bPostCustomer('Tijs', 'Verkoyen');
		$customer->setDeliveryAddress(new bPostAddress('Kerkstraat', '108', '9050', 'Gentbrugge'));

		$order = new bPostOrder($orderId, 'OPEN');

		$order->setStatus('OPEN');
		$order->setCostCenter('CostCenter1');
		$order->addOrderLine('Item 1', 10);
		$order->addOrderLine('Item 2', 20);
		$order->setCustomer($customer);
		$order->setDeliveryMethod(new bPostDeliveryMethodAtHome());
		$order->setTotal(100);

		$this->bpost->createOrReplaceOrder($order);

		$var = $this->bpost->createNationalLabel($orderId, 1, null, true);

		$this->assertArrayHasKey('orderReference', $var);
		$this->assertArrayHasKey('barcode', $var);
		$this->assertArrayHasKey('pdf', $var);
	}

	/**
	 * Tests bpost->createOrderAndNationalLabel
	 */
	public function testCreateOrderAndNationalLabel()
	{
		$orderId = time();

		$customer = new bPostCustomer('Tijs', 'Verkoyen');
		$customer->setDeliveryAddress(new bPostAddress('Kerkstraat', '108', '9050', 'Gentbrugge'));

		$order = new bPostOrder($orderId, 'OPEN');

		$order->setStatus('OPEN');
		$order->setCostCenter('CostCenter1');
		$order->addOrderLine('Item 1', 10);
		$order->addOrderLine('Item 2', 20);
		$order->setCustomer($customer);
		$order->setDeliveryMethod(new bPostDeliveryMethodAtHome());
		$order->setTotal(100);

		$var = $this->bpost->createOrderAndNationalLabel($order, 1);

		$this->assertArrayHasKey('orderReference', $var);
		$this->assertArrayHasKey('barcode', $var);
	}
}

