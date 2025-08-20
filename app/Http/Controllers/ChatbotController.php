<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenView;

class ChatbotController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $userMessage = strtolower(trim($request->input('message')));
            
            // Log para depuración
            \Log::info('Chatbot received message: ' . $userMessage);
            
            // Manejar diferentes flujos de conversación
            switch ($userMessage) {
                case 'start':
                case 'inicio':
                case 'volver_inicio':
                    return $this->inicioConversacion();
                    
                case 'consultar_orden':
                    return $this->PreguntarPorCodigoOrden();
                    
                case 'preguntas_frecuentes':
                    return $this->mostrarOpcionDePreguntasFrecuentes();
                    
                case 'contactar_agente':
                    return $this->contactarAgente();
                    
                case 'faq_consulta':
                    return $this->mostrarRespuestaComoConsultarOrden();
                    
                case 'faq_entrega':
                    return $this->mostrarRespuestaTiempoDeEnviorOrden();
                    
                default:
                    // Verificar si es un número de orden (contiene números)
                    if (preg_match('/\d/', $userMessage)) {
                        return $this->buscarOrden(strtoupper($userMessage));
                    }
                    
                    return $this->ManejadorDeMensajeDesconocido();
            }
        } catch (\Exception $e) {
            \Log::error('General chatbot error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'reply' => '😔 Ocurrió un error inesperado. Por favor, intenta nuevamente.',
                'options' => [
                    ['text' => '🏠 Volver al inicio', 'value' => 'volver_inicio']
                ]
            ]);
        }
    }

    private function inicioConversacion()
    {
        return response()->json([
            'reply' => '¡Hola! 👋 Bienvenido al portal de consulta de órdenes. ¿En qué puedo ayudarte?',
            'options' => [
                ['text' => '📋 Consultar mi orden', 'value' => 'consultar_orden'],
                ['text' => '❓ Preguntas frecuentes', 'value' => 'preguntas_frecuentes'],
                ['text' => '📞 Contactar agente', 'value' => 'contactar_agente']
            ]
        ]);
    }

    private function PreguntarPorCodigoOrden()
    {
        return response()->json([
            'reply' => '📋 Por favor, ingresa el número de tu orden:'
        ]);
    }

    private function mostrarOpcionDePreguntasFrecuentes()
    {
        return response()->json([
            'reply' => '❓ Preguntas Frecuentes\n\nSelecciona una pregunta:',
            'options' => [
                ['text' => '¿Cómo consulto mi orden?', 'value' => 'faq_consulta'],
                ['text' => '¿Cuánto demora la entrega?', 'value' => 'faq_entrega'],
                ['text' => '🏠 Volver al inicio', 'value' => 'volver_inicio']
            ]
        ]);
    }

    private function contactarAgente()
    {
        $whatsappNumber = "+521234567890";
        $message = urlencode("Hola, vengo del chatbot del portal de órdenes. Necesito ayuda.");
        $whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$message}";

        return response()->json([
            'reply' => "📱 Te voy a conectar con uno de nuestros agentes.\n\nHaz clic en el botón para abrir WhatsApp:",
            'options' => [
                ['text' => '💬 Abrir WhatsApp', 'value' => 'whatsapp_link', 'url' => $whatsappUrl],
                ['text' => '🏠 Volver al inicio', 'value' => 'volver_inicio']
            ]
        ]);
    }

    private function mostrarRespuestaComoConsultarOrden()
    {
        return response()->json([
            'reply' => "📋 ¿Cómo consulto mi orden?\n\nPara consultar tu orden:\n1. Selecciona 'Consultar mi orden'\n2. Ingresa el número de tu orden\n3. Te mostraré toda la información disponible\n\nSi no encuentras tu orden, puedes contactar a nuestro agente.",
            'options' => [
                ['text' => '📋 Consultar mi orden', 'value' => 'consultar_orden'],
                ['text' => '📞 Contactar agente', 'value' => 'contactar_agente'],
                ['text' => '🏠 Volver al inicio', 'value' => 'volver_inicio']
            ]
        ]);
    }

    private function mostrarRespuestaTiempoDeEnviorOrden()
    {
        return response()->json([
            'reply' => "🚚 ¿Cuánto demora la entrega?\n\nLos tiempos de entrega varían según:\n• Ubicación geográfica\n• Tipo de producto\n• Disponibilidad del proveedor\n\nPuedes consultar el estado específico de tu orden o contactar a nuestro agente para más detalles.",
            'options' => [
                ['text' => '📋 Consultar mi orden', 'value' => 'consultar_orden'],
                ['text' => '📞 Contactar agente', 'value' => 'contactar_agente'],
                ['text' => '🏠 Volver al inicio', 'value' => 'volver_inicio']
            ]
        ]);
    }

    private function buscarOrden($orderNumber)
    {
        try {
            \Log::info('Searching for order: ' . $orderNumber);
            
            // Intentar primero una consulta simple
            $ordenes = OrdenView::where('nro_orden', 'LIKE', "%{$orderNumber}%")->get();
            
            \Log::info('Found orders count: ' . $ordenes->count());

            if ($ordenes->isEmpty()) {
                return response()->json([
                    'reply' => "❌ No encontré ninguna orden con: *{$orderNumber}*\n\n¿Qué te gustaría hacer?",
                    'options' => [
                        ['text' => '🔄 Intentar de nuevo', 'value' => 'consultar_orden'],
                        ['text' => '📞 Contactar agente', 'value' => 'contactar_agente'],
                        ['text' => '🏠 Volver al inicio', 'value' => 'volver_inicio']
                    ]
                ]);
            }

            $orden = $ordenes->first();
            \Log::info('Order data: ', $orden->toArray());
            
            $info = "✅ *Orden encontrada:*\n\n";
            $info .= "📋 *Número:* {$orden->nro_orden}\n";
            
            if (isset($orden->fecha_entrega)) {
                $info .= "📅 *Fecha entrega:* {$orden->fecha_entrega}\n";
            }
            if (isset($orden->fecha_guia)) {
                $info .= "📅 *Fecha guia:* {$orden->fecha_guia}\n";
            }
            if (isset($orden->nombre_entidad)) {
                $info .= "🏢 *Cliente:* {$orden->nombre_entidad}\n";
            }

            return response()->json([
                'reply' => $info,
                'options' => [
                    ['text' => '📞 Contactar agente', 'value' => 'contactar_agente'],
                    ['text' => '🔍 Buscar otra orden', 'value' => 'consultar_orden'],
                    ['text' => '🏠 Volver al inicio', 'value' => 'volver_inicio']
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error searching order: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'reply' => '😔 Error: ' . $e->getMessage() . '\n\nPor favor, intenta nuevamente.',
                'options' => [
                    ['text' => '🔄 Intentar de nuevo', 'value' => 'consultar_orden'],
                    ['text' => '📞 Contactar agente', 'value' => 'contactar_agente'],
                    ['text' => '🏠 Volver al inicio', 'value' => 'volver_inicio']
                ]
            ]);
        }
    }

    private function ManejadorDeMensajeDesconocido()
    {
        return response()->json([
            'reply' => 'No entendí tu mensaje. ¿En qué puedo ayudarte?',
            'options' => [
                ['text' => '📋 Consultar mi orden', 'value' => 'consultar_orden'],
                ['text' => '❓ Preguntas frecuentes', 'value' => 'preguntas_frecuentes'],
                ['text' => '📞 Contactar agente', 'value' => 'contactar_agente']
            ]
        ]);
    }
}