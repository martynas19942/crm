<?php

namespace OroCRM\Bundle\MagentoBundle\Tests\Functional\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use OroCRM\Bundle\MagentoBundle\Entity\Address;
use OroCRM\Bundle\MagentoBundle\Entity\Customer;

class LoadAddressDiscoveryData extends AbstractFixture
{
    /**
     * @var array
     */
    protected static $data = [
        [
            'reference' => 'discovery_customer1',
            'email' => 'discovery@example.com',
            'firstName' => 'fn1',
            'addresses' => [
                [
                    'type' => 'billing',
                    'postalCode' => 123456
                ]
            ]
        ],
        [
            'reference' => 'discovery_customer2',
            'email' => 'discovery@example.com',
            'firstName' => 'fn1',
            'addresses' => [
                [
                    'type' => 'billing',
                    'postalCode' => 555555
                ]
            ]
        ],
        [
            'reference' => 'discovery_customer3',
            'email' => 'discovery@example.com',
            'firstName' => 'fn2',
            'addresses' => [
                [
                    'type' => 'shipping',
                    'postalCode' => 123456
                ]
            ]
        ],
        [
            'reference' => 'discovery_customer4',
            'email' => 'discovery@example.com',
            'firstName' => 'fn2',
            'addresses' => [
                [
                    'type' => 'shipping',
                    'postalCode' => 555555
                ]
            ]
        ],
        [
            'reference' => 'discovery_customer5',
            'email' => 'discovery@example.com',
            'firstName' => 'fn2',
            'addresses' => [
                [
                    'type' => 'billing',
                    'postalCode' => 123456
                ],
                [
                    'type' => 'shipping',
                    'postalCode' => 555555
                ]
            ]
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $addressTypeRepo = $manager->getRepository('OroAddressBundle:AddressType');

        foreach (self::$data as $item) {
            $customer = new Customer();
            $customer->setEmail($item['email']);
            $customer->setFirstName($item['firstName']);

            foreach ($item['addresses'] as $addressData) {
                $address = new Address();
                $address->setPostalCode($addressData['postalCode']);
                $address->addType($addressTypeRepo->findOneBy(['name' => $addressData['type']]));

                $customer->addAddress($address);
                $manager->persist($address);
            }

            $manager->persist($customer);
            $manager->flush();

            $this->setReference($item['reference'], $customer);
        }
    }
}
