<?php

declare(strict_types=1);

namespace App\Presentation\Admin\Users\Factory;

use App\Model\User\Entity\Address;
use App\Model\User\Entity\User;
use App\Model\User\Enum\AddressType;
use App\Model\User\Repository\AddressRepository;
use App\Model\User\Repository\UserRepository;
use Nette\Application\UI\Form;
use stdClass;

class AddressFormFactory
{
    public array $onSave = [];

    public function __construct(
        private AddressRepository $addressRepository,
        private UserRepository $userRepository
    ) {
    }

    public function create(?int $userId = null, ?int $addressId = null): Form
    {
        $form = new Form;

        $types = array_column(AddressType::cases(), 'value', 'value');
        $form->addSelect('type', 'Type', $types)
            ->setRequired('Type is required');

        $form->addText('street', 'Street')
            ->setRequired('Street is required');

        $form->addText('city', 'City')
            ->setRequired('City is required');

        $form->addText('zip', 'ZIP')
            ->setRequired('ZIP is required');

        $form->addText('country', 'Country')
            ->setDefaultValue('CZ')
            ->setRequired('Country is required');

        $form->addHidden('userId', (string)$userId);
        $form->addHidden('addressId', (string)$addressId);

        $form->addSubmit('save', 'Save Address');

        if ($addressId) {
            $address = $this->addressRepository->getById($addressId);
            if ($address) {
                $form->setDefaults([
                    'type' => $address->type->value,
                    'street' => $address->street,
                    'city' => $address->city,
                    'zip' => $address->zip,
                    'country' => $address->country,
                    'addressId' => $address->id,
                    'userId' => $address->user->id
                ]);
            }
        }

        $form->onSuccess[] = function (Form $form, stdClass $values) {
            $this->processForm($form, $values);
        };

        return $form;
    }

    public function onSave(Address $address): void
    {
        foreach ($this->onSave as $callback) {
            $callback($address);
        }
    }

    private function processForm(Form $form, stdClass $values): void
    {
        if ($values->addressId) {
            $address = $this->addressRepository->getById((int)$values->addressId);
            if (!$address) {
                $form->addError('Address not found');
                return;
            }
        } else {
            $user = $this->userRepository->getById((int)$values->userId);
            if (!$user) {
                $form->addError('User not found');
                return;
            }
            // Create dummy first, attributes will be overwritten
            $address = new Address(
                $user,
                AddressType::from($values->type),
                $values->street,
                $values->city,
                $values->zip,
                $values->country
            );
        }

        $address->type = AddressType::from($values->type);
        $address->street = $values->street;
        $address->city = $values->city;
        $address->zip = $values->zip;
        $address->country = $values->country;

        $this->addressRepository->save($address);
        
        $this->onSave($address);
    }
}
