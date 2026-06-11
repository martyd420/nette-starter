<?php

declare(strict_types=1);

namespace App\Presentation\Admin\Users\Factory;

use App\Model\User\Entity\Address;
use App\Model\User\Enum\AddressType;
use App\Model\User\Repository\AddressRepository;
use App\Model\User\Repository\UserRepository;
use Nette\Application\UI\Form;
use stdClass;

class AddressFormFactory
{
	public function __construct(
		private AddressRepository $addressRepository,
		private UserRepository $userRepository,
	) {
	}

	/**
	 * The owning user and the edited address are fixed server-side;
	 * they must not come from the submitted form data.
	 *
	 * @param callable(Address): void $onSave
	 */
	public function create(?int $userId, ?int $addressId, callable $onSave): Form
	{
		$form = new Form();

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

		$form->addSubmit('save', 'Save Address');

		if ($addressId !== null) {
			$address = $this->addressRepository->getById($addressId);
			if ($address) {
				$form->setDefaults([
					'type' => $address->type->value,
					'street' => $address->street,
					'city' => $address->city,
					'zip' => $address->zip,
					'country' => $address->country,
				]);
			}
		}

		$form->onSuccess[] = function (Form $form, stdClass $values) use ($userId, $addressId, $onSave): void {
			$address = $this->processForm($form, $values, $userId, $addressId);
			if ($address) {
				$onSave($address);
			}
		};

		return $form;
	}

	private function processForm(Form $form, stdClass $values, ?int $userId, ?int $addressId): ?Address
	{
		if ($addressId !== null) {
			$address = $this->addressRepository->getById($addressId);
			if (!$address) {
				$form->addError('Address not found');

				return null;
			}

			$address->type = AddressType::from($values->type);
			$address->street = $values->street;
			$address->city = $values->city;
			$address->zip = $values->zip;
			$address->country = $values->country;
		} else {
			$user = $userId !== null ? $this->userRepository->getById($userId) : null;
			if (!$user) {
				$form->addError('User not found');

				return null;
			}

			$address = new Address(
				$user,
				AddressType::from($values->type),
				$values->street,
				$values->city,
				$values->zip,
				$values->country,
			);
		}

		$this->addressRepository->save($address);

		return $address;
	}
}
