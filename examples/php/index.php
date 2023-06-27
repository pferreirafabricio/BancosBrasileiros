<?php

require __DIR__ . '/vendor/autoload.php';

use Guibranco\BancosBrasileiros\Bank;

echo "Running...\n";

$jsonPath = __DIR__ . "/vendor/guibranco/bancos-brasileiros/data/bancos.json";

$content = file_get_contents($jsonPath);
$data = json_decode(
    preg_replace('/[[:^print:]]/', '', $content),
    true,
    512,
    JSON_THROW_ON_ERROR
);

foreach (($data ?: []) as $value) {
    $bank = convertFromArray($value, new Bank());

    echo "\n{$bank->COMPE}\t[CNPJ: {$bank->Document}]\t{$bank->LongName}\n";

    echo "\tTipo: " . ($bank->Type ?: '') . "\n";
    echo "\tBoleto: " . ($bank->Charge ?: false) . "\n";
    echo "\tTED/DOC: " . ($bank->CreditDocument ?: false) . "\n";
    echo "\tPIX: " . ($bank->PixType ?: "False") . "\n";
    echo "\tPortabilidade: " . ($bank->SalaryPortability ?: "False") . "\n";
    echo "\tAtualizado em: {$bank->DateUpdated}";
}

function convertFromArray(array $data, &$object)
{
    if (!$data || count($data) === 0) return $object;

    foreach ($data as $propertyName => $value) {
        if (!property_exists(Bank::class, $propertyName)) continue;

        $object->$propertyName = $value;
    }

    return $object;
}

readline();
