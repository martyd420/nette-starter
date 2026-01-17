<?php

declare(strict_types=1);

namespace App\Presentation\Admin\Users;

use App\Model\Factory\FormFactory;
use App\Model\User\Entity\User;
use App\Model\User\Enum\UserRole;
use App\Model\User\Enum\UserStatus;
use App\Model\User\Facade\UserAdminFacade;
use App\Model\User\Factory\UsersGridFactory;
use App\Model\User\Repository\AddressRepository;
use App\Model\User\Repository\UserRepository;
use App\Presentation\Accessory\BootstrapHorizontalRenderer;
use App\Presentation\Accessory\BootstrapRenderer;
use App\Presentation\Admin\BaseAdminPresenter;
use App\Presentation\Admin\Users\Factory\AddressFormFactory;
use Contributte\Datagrid\Datagrid;
use Nette\Application\UI\Form;
use Nette\DI\Attributes\Inject;

final class UsersPresenter extends BaseAdminPresenter
{
	#[Inject]
	public UsersGridFactory $usersGridFactory;

	#[Inject]
	public UserRepository $userRepository;

	#[Inject]
	public AddressRepository $addressRepository;

	#[Inject]
	public UserAdminFacade $userAdminFacade;

	#[Inject]
	public AddressFormFactory $addressFormFactory;

	#[Inject]
	public FormFactory $formFactory; // for bootstrap example

	private ?User $editedUser = null;
	private ?int $editedAddressId = null;

	public function actionEdit(int $id): void
	{
		$this->editedUser = $this->userRepository->getById($id);
		if (!$this->editedUser) {
			$this->flashMessage('User not found', 'danger');
			$this->redirect('default');
		}

		$this['userForm']->setDefaults([
			'email' => $this->editedUser->email,
			'role' => $this->editedUser->roles[0]->value ?? null,
			'status' => $this->editedUser->status->value,
			'firstName' => $this->editedUser->getProfile()?->firstName,
			'lastName' => $this->editedUser->getProfile()?->lastName,
		]);
	}

	public function renderEdit(int $id): void
	{
		$this->template->editedUser = $this->editedUser;
	}

	public function actionAddressAdd(int $userId): void
	{
		$this->editedUser = $this->userRepository->getById($userId);
		if (!$this->editedUser) {
			$this->flashMessage('User not found', 'danger');
			$this->redirect('default');
		}
	}

	public function renderAddressAdd(int $userId): void
	{
		$this->template->editedUser = $this->editedUser;
		$this->template->isEdit = false;
		$this->setView('address');
	}

	public function actionAddressEdit(int $id): void
	{
		$address = $this->addressRepository->getById($id);
		if (!$address) {
			$this->flashMessage('Address not found', 'danger');
			$this->redirect('default');
		}
		$this->editedAddressId = $id;
		$this->editedUser = $address->user;
	}

	public function renderAddressEdit(int $id): void
	{
		$this->template->editedUser = $this->editedUser;
		$this->template->isEdit = true;
		$this->setView('address');
	}

	protected function createComponentAddressForm(): Form
	{
		$userId = $this->editedUser ? $this->editedUser->id : null;
		$form = $this->addressFormFactory->create($userId, $this->editedAddressId);

		$this->addressFormFactory->onSave[] = function ($address) {
			$this->flashMessage('Address saved', 'success');
			$this->redirect('edit', ['id' => $address->user->id]);
		};

		return $form;
	}

	protected function createComponentUserForm(): Form
	{
		$form = new Form();

		$form->addText('email', 'Email')
			->setRequired('Email is required')
			->addRule(Form::EMAIL, 'Invalid email format');

		$roles = array_column(UserRole::cases(), 'value', 'value');
		$form->addSelect('role', 'Role', $roles)
			->setRequired('Role is required');

		$statuses = array_column(UserStatus::cases(), 'value', 'value');
		$form->addSelect('status', 'Status', $statuses)
			->setRequired('Status is required');

		$form->addText('firstName', 'First Name');
		$form->addText('lastName', 'Last Name');

		$form->addSubmit('save', 'Save');

		$form->onSuccess[] = [$this, 'processUserForm'];

		return $form;
	}

	public function processUserForm(Form $form, \stdClass $values): void
	{
		if (!$this->editedUser) {
			$form->addError('User not found');

			return;
		}

		try {
			$this->userAdminFacade->updateUser($this->editedUser->id, (array) $values);
			$this->flashMessage('User updated', 'success');
			$this->redirect('default');
		} catch (\Exception $e) {
			$form->addError($e->getMessage());
		}
	}

	public function createComponentUsersGrid(): Datagrid
	{
		return $this->usersGridFactory->create();
	}

	protected function createComponentBootstrapForm(): Form
	{
		$form = $this->formFactory->create();
		$form->setRenderer(new BootstrapRenderer());

		$form->addText('name', 'First Name');
		$form->addText('random', 'Random number');
		$form->addSelect('role', 'Lorem', ['Lorem ipsum', 'Lorem Lorem ipsum']);
		$form->addInteger('lorem', 'I Accept Lorem ipsum dolor sit amet');
		$form->addCheckbox('dolor', 'I Lorem dolor only')->setDefaultValue(true);
		$form->addRadioList('radio', 'Select option', ['Lorem ipsum', 'Lorem Lorem ipsum'])->setDefaultValue(0);
		$form->addSubmit('send', 'Send');


		return $form;
	}

	protected function createComponentHorizontalBootstrapForm(): Form
	{
		$form = $this->formFactory->create();
		$form->setRenderer(new BootstrapHorizontalRenderer());

		$form->addText('name', 'Name');
		$form->addText('email', 'Email');
		$form->addText('password', 'Password');
		$form->addSelect('role', 'Lorem', ['Lorem ipsum', 'Lorem Lorem ipsum']);
		$form->addCheckbox('remember', 'This is a checkbox. Dolor ipsum lorem!')->setDefaultValue(true);
		$form->addRadioList('radio', 'Select option', ['Lorem ipsum', 'Lorem Lorem ipsum', 'Other'])
            ->setDefaultValue(1);
		$form->addSubmit('send', 'Send');


		return $form;
	}


}
