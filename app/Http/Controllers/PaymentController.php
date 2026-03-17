<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Processa uma nova compra através dos gateways disponíveis.
     */
    public function store(Request $request)
    {
        // 1. Validação básica dos dados de entrada
        $validated = $request->validate([
            'amount'      => 'required|numeric|min:0.01',
            'card_number' => 'required|string|min:16',
        ]);

        // 2. Busca os gateways ativos por ordem de prioridade
        $gateways = Gateway::where('is_active', true)
            ->orderBy('priority', 'asc')
            ->get();

        if ($gateways->isEmpty()) {
            return response()->json(['error' => 'Nenhum gateway de pagamento disponível.'], 503);
        }

        // 3. Lógica de tentativa (Failover)
        foreach ($gateways as $gateway) {
            try {
                // Aqui simularíamos a chamada para a API externa do Gateway
                // Para o desafio, assumimos que se chegar no Gateway 2, ele funciona
                if ($gateway->name === 'Gateway 1') {
                    throw new \Exception("Falha na comunicação com o Gateway 1");
                }

                // Registro da transação no banco (Opcional, mas boa prática)
                Transaction::create([
                    'gateway_id' => $gateway->id,
                    'amount'     => $validated['amount'],
                    'status'     => 'success'
                ]);

                return response()->json([
                    'message' => "Venda realizada via {$gateway->name}",
                    'gateway' => $gateway->name,
                    'amount'  => $validated['amount']
                ], 200);

            } catch (\Exception $e) {
                // Logar o erro internamente e tentar o próximo gateway do loop
                continue; 
            }
        }

        return response()->json(['error' => 'Todos os gateways falharam no processamento.'], 500);
    }
}