@extends('layouts.app')

@section('cabecera') 
@endsection

@section('estilos')
<style>
    /* Botón flotante para abrir/cerrar el chat */
    .chat-toggle {
        background-color: var(--bs-primary) !important;
        color: white;
        border: none;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 10000;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        cursor: pointer;
    }

    .chat-box {
        display: none;
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 320px;
        height: 450px;
        background: white;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        overflow: hidden;
        z-index: 9999;
        display: flex;
        flex-direction: column;
    }

    .chat-header {
        background-color: var(--bs-primary);
        color: white;
        padding: 10px;
        text-align: center;
        font-weight: bold;
    }

    .chat-messages {
        flex: 1;
        padding: 10px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .chat-input-area {
        display: flex;
        padding: 5px;
        border-top: 1px solid #ccc;
        background: #f9f9f9;
    }

    .chat-input-area input {
        flex: 1;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        outline: none;
    }

    .chat-input-area button {
        margin-left: 5px;
        padding: 8px 12px;
        background: var(--bs-primary);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .chat-input-area button:hover {
        background: #0b5ed7;
    }

    /* Estilos de mensajes */
    .message {
        background: #e9ecef;
        padding: 6px;
        border-radius: 4px;
        max-width: 80%;
        word-wrap: break-word;
    }

    /* Usuario (lado derecho, color primario) */
    .message.user {
        align-self: flex-start;
        background: var(--bs-primary);
        color: white;
    }

    /* Bot (lado izquierdo, gris) */
    .message.bot {
        align-self: flex-end;
        background: #f1f3f5;
        color: #212529;
    }
</style>

@endsection

@section('cuerpo')

<div class="container my-5">
    <div class="p-5 text-center bg-body-tertiary rounded-3">
        <h1 class="text-body-emphasis">Consulta del Estado de su Orden</h1>
        <p class="col-lg-8 mx-auto fs-5 text-muted">
            Ingrese el código de su orden para consultar el estado actual del trámite y revisar cualquier documento o archivo adjunto relacionado. Esta herramienta está diseñada para brindarle información actualizada y transparente sobre el proceso.
        </p>

        {{-- Formulario de búsqueda --}}
        <form method="GET" action="{{ route('home') }}">
            <div class="d-inline-flex gap-2 mb-5">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="inputNumeroDoc" class="col-form-label">Código</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" name="codigo" id="inputNumeroDoc" class="form-control" value="{{ request('codigo') }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Tabla --}}
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>OCAM</th>
                            <th>ENTIDAD</th>
                            <th>EMPRESA</th>
                            <th>FECHA PUBLICACION</th>
                            <th>FECHA INICIO ENTREGA</th>
                            <th>FECHA FIN ENTREGA</th>
                            <th>FECHA DESPACHO</th>
                            <th>GUIA</th>
                            <th>FECHA ENTREGA REAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($ordenes) && count($ordenes) > 0)
                            @foreach ($ordenes as $orden)
                                <tr>
                                    <td>{{ $orden['nro_orden'] }}</td>
                                    <td>{{ $orden['nombre_entidad'] }}</td>
                                    <td>{{ $orden['nombre_empresa'] }}</td>
                                    <td>{{ $orden['fecha_publicacion'] }}</td>
                                    <td>{{ $orden['inicio_entrega'] }}</td>
                                    <td>{{ $orden['fecha_entrega'] }}</td>
                                    <td>{{ $orden['fecha_guia'] }}</td>
                                    <td class="text-center">
                                        @if ($orden->guia)
                                            <a href="{{ route('guia.ver', $orden->id) }}" target="_blank" title="Ver guía">
                                                <i class="fa-solid fa-file-pdf fa-lg text-danger"></i>
                                            </a>
                                        @else
                                            <i class="fa-solid fa-file-pdf fa-lg text-secondary" title="Guía no disponible"></i>
                                        @endif
                                    </td>
                                    <td>{{ $orden['fecha_entrega_real'] }}</td>
                                </tr>
                            @endforeach
                        @elseif(request('codigo'))
                            <tr>
                                <td colspan="9" class="text-center">
                                    No se encontraron resultados para el código: <strong>{{ request('codigo') }}</strong>.
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="9" class="text-center text-muted">
                                    Ingrese un código y presione "Buscar" para ver resultados.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Botón flotante -->
<button class="chat-toggle" id="chat-toggle">🤖</button>

<!-- Caja de chat -->
<div id="chat-box" class="chat-box" style="display: none">
    <div class="chat-header">Chatbot</div>
    <div id="chat-messages" class="chat-messages"></div>
    <div class="chat-input-area">
        <input type="text" id="chat-input" placeholder="Escribe un mensaje...">
        <button id="send-btn">Enviar</button>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const chatToggle = document.getElementById("chat-toggle");
    const chatBox = document.getElementById("chat-box");
    const chatMessages = document.getElementById("chat-messages");
    const chatInput = document.getElementById("chat-input");
    const sendBtn = document.getElementById("send-btn");

    chatToggle.addEventListener("click", () => {
        chatBox.style.display = (chatBox.style.display === "none" || chatBox.style.display === "") ? "flex" : "none";
    });

    function appendMessage(text, sender = "user") {
        const messageElement = document.createElement("div");
        messageElement.classList.add("message", sender);
        messageElement.textContent = text;
        chatMessages.appendChild(messageElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    async function sendMessage() {
        const messageText = chatInput.value.trim();
        if (messageText !== "") {
            appendMessage(messageText, "user");
            chatInput.value = "";

            // Enviar al backend
            try {
                const response = await fetch("/chatbot", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ message: messageText })
                });

                const data = await response.json();
                appendMessage(data.reply, "bot");

            } catch (error) {
                appendMessage("Error al conectar con el servidor.", "bot");
            }
        }
    }

    sendBtn.addEventListener("click", sendMessage);
    chatInput.addEventListener("keypress", (e) => {
        if (e.key === "Enter") sendMessage();
    });
</script>


@endsection
