<?php

declare(strict_types = 1);

namespace Cheppers\MiniCrm\DataTypes\Contact;

class BusinessRequest extends ContactRequestBase
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $industry;

    /**
     * @var string
     */
    public $region;

    /**
     * @var string
     */
    public $vatNumber;

    /**
     * @var string
     */
    public $registrationNumber;

    /**
     * @var string
     */
    public $bankAccount;

    /**
     * @var string
     */
    public $swift;

    /**
     * @var int
     */
    public $employees;

    /**
     * @var int
     */
    public $yearlyRevenue;

    /**
     * @var string
     */
    public $foundingYear;

    /**
     * @var string
     */
    public $mainActivity;

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();

        foreach (array_keys(get_object_vars($this)) as $key) {
            switch ($key) {
                case 'type':
                    $data['Type'] = 'Cég';
                    break;
                case 'name':
                    $data['Name'] = $this->name;
                    break;
                case 'url':
                    $data['Url'] = $this->url;
                    break;
                case 'industry':
                    $data['Industry'] = $this->industry;
                    break;
                case 'region':
                    $data['Region'] = $this->region;
                    break;
                case 'vatNumber':
                    $data['VatNumber'] = $this->vatNumber;
                    break;
                case 'registrationNumber':
                    $data['RegistrationNumber'] = $this->registrationNumber;
                    break;
                case 'bankAccount':
                    $data['BankAccount'] = $this->bankAccount;
                    break;
                case 'swift':
                    $data['Swift'] = $this->swift;
                    break;
                case 'employees':
                    $data['Employees'] = $this->employees;
                    break;
                case 'yearlyRevenue':
                    $data['YearlyRevenue'] = $this->yearlyRevenue;
                    break;
                case 'foundingYear':
                    $data['FoundingYear'] = $this->foundingYear;
                    break;
                case 'mainActivity':
                    $data['MainActivity'] = $this->mainActivity;
                    break;
            }
        }

        return $data;
    }
}
