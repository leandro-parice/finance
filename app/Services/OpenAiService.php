<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function readImage($text, $image)
    {
        $response = Http::withToken($this->apiKey)
            ->post('https://api.openai.com/v1/chat/completions', [
                "model" => "gpt-4o-2024-08-06",
                "messages" => [
                    ['role' => 'system', 'content' => 'Você é um assistente especializado em leitura de imagens de cupons de compras em supermercados.'],
                    ['role' => 'system', 'content' => 'Enviarei imagens para você transcrever o conteúdo.'],
                    ['role' => 'system', 'content' => 'Por favor, me informe o nome do supermercado, o valor total da compra, a data da compra e a lista de produtos comprados.'],
                    ['role' => 'system', 'content' => 'Sobre os produtos, geralmente os dados são tabelados e seguem a ordem de id do item na nota, código de barras, descrição e valor do produto.'],
                    ['role' => 'system', 'content' => 'A descrição pode variar de mercado para mercado, mas vem basicamente o nome e em alguns casos a quantidade de valor unitário.'],
                    ['role' => 'system', 'content' => 'Você pode verificar se o valor total do item corresponde ao valor unitário multiplicado pela quantidade.'],
                    ['role' => 'system', 'content' => 'Algumas descrições também vem a taxa de imposto aplicada ao item, mas por hora esse valor pode ser ignorado.'],
                    ['role' => 'system', 'content' => 'As vezes, alguns items correspondem a descontos aplicados na nota, e isso você pode conferir somando os itens e descontados esses valores para conferir com o valor total da nota.'],
                    ['role' => 'system', 'content' => 'Se precisar de mais informações, deixe anotado no item observacoes do JSON que você irá me retornar.'],
                    ['role' => 'user', 'content' => [
                        ['type' => 'text', 'text' => $text],
                        ['type' => 'image_url', 'image_url' => ['url' => 'data:image/jpeg;base64,' . $image]]
                    ]],
                    ['role' => 'system', 'content' => 'Exemplo de resposta esperada: 
            {
                "mercado": "Nome do Supermercado",
                "valor_total": "99.99",
                "data_compra": "DD/MM/AAAA",
                "observacoes": "Observações adicionais",
                "produtos": [
                    {
                        "nome": "Produto 1",
                        "valor_unitario": "9.99",
                        "quantidade": "2",
                        "valor_total": "19.98",
                        "codigo_barras": "1234567890"
                    },
                    {
                        "nome": "Produto 2",
                        "valor_unitario": "5.00",
                        "quantidade": "1",
                        "valor_total": "5.00"
                        "codigo_barras": "123123"
                    }
                ]
            }'],
                    ['role' => 'user', 'content' => [
                        ['type' => 'text', 'text' => $text],
                        ['type' => 'image_url', 'image_url' => ['url' => 'data:image/jpeg;base64,' . $image]]
                    ]],
                ],
                "response_format" => [
                    "type" => "json_schema",
                    "json_schema" => [
                        "name" => "cupom_mercado",
                        "schema" => [
                            "type" => "object",
                            "properties" => [
                                "nome_supermercado" => [
                                    "type" => "string",
                                    "description" => "O nome do supermercado onde a compra foi realizada."
                                ],
                                "valor_total" => [
                                    "type" => "number",
                                    "description" => "O valor total da compra realizada."
                                ],
                                "data_compra" => [
                                    "type" => "string",
                                    "description" => "A data em que a compra foi realizada."
                                ],
                                "produtos" => [
                                    "type" => "array",
                                    "items" => [
                                        "type" => "object",
                                        "description" => "Uma lista de produtos comprados.",
                                        "properties" => [
                                            "nome" => [
                                                "type" => "string",
                                                "description" => "O nome do produto."
                                            ],
                                            "valor_unitario" => [
                                                "type" => "number",
                                                "description" => "O valor unitário do produto."
                                            ],
                                            "quantidade" => [
                                                "type" => "number",
                                                "description" => "A quantidade do produto comprada."
                                            ],
                                            "valor_total" => [
                                                "type" => "number",
                                                "description" => "O valor total do produto (unitario * quantidade)."
                                            ],
                                            "codigo_barras" => [
                                                "type" => "string",
                                                "description" => "O código de barras do produto."
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ], 
                ]
            ]);

        if ($response->successful()) {
            return $response->json('choices.0.message.content') ?? 'Sem resposta.';
        }

        return 'Erro ao conectar com o ChatGPT: ' . $response->body();
    }
}