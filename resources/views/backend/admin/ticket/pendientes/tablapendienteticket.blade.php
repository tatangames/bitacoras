<style>
    .img-thumb {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 6px;
        cursor: pointer;
        border: 2px solid #dee2e6;
        transition: transform 0.15s, border-color 0.15s;
    }
    .img-thumb:hover {
        transform: scale(1.1);
        border-color: #6c757d;
    }
    .btn-pdf {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.75rem;
        padding: 3px 9px;
        border-radius: 20px;
        border: 1px solid #dc3545;
        color: #dc3545;
        background: #fff5f5;
        text-decoration: none;
        font-weight: 600;
        transition: background 0.15s, color 0.15s;
    }
    .btn-pdf:hover {
        background: #dc3545;
        color: #fff;
        text-decoration: none;
    }
    .sin-doc {
        font-size: 0.75rem;
        color: #adb5bd;
        font-style: italic;
    }

    /* Modal imagen */
    #modalImagen .modal-body {
        text-align: center;
        background: #1a1a1a;
        padding: 10px;
    }
    #modalImagen img {
        max-width: 100%;
        max-height: 80vh;
        border-radius: 6px;
    }
</style>

<table id="tabla" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th style="width: 8%">Fecha</th>
        <th style="width: 8%">Estado</th>
        <th style="width: 20%">Tipo Incidente</th>
        <th style="width: 8%; text-align:center">Documento</th>
    </tr>
    </thead>
    <tbody>
    @foreach($arrayBitacoraIncidencias as $dato)
        <tr>
            <td data-order="{{ strtotime($dato->fecha) }}">
                {{ $dato->fechaFormat }}
            </td>

            <td>
                @php
                    $badges = [
                        0 => ['color' => 'warning',  'label' => 'Pendiente'],
                        1 => ['color' => 'primary',  'label' => 'En Proceso'],
                    ];
                    $badge = $badges[$dato->estado] ?? ['color' => 'secondary', 'label' => 'Desconocido'];
                @endphp
                <span class="badge bg-{{ $badge['color'] }}">{{ $badge['label'] }}</span>
            </td>

            <td>{{ $dato->tipo_incidente }}</td>

            {{-- ── Columna Documento ───────────────────────────────── --}}
            <td style="text-align:center; vertical-align:middle;">
                @if($dato->documento)
                    @php
                        $ext = strtolower(pathinfo($dato->documento, PATHINFO_EXTENSION));
                        $url = asset('storage/archivos/' . $dato->documento);
                    @endphp

                    @if(in_array($ext, ['jpg','jpeg','png']))
                        {{-- Miniatura clickeable --}}
                        <img src="{{ $url }}"
                             class="img-thumb"
                             alt="Captura"
                             onclick="verImagen('{{ $url }}')"
                             title="Ver imagen">

                    @elseif($ext === 'pdf')
                        {{-- Botón descarga PDF --}}
                        <a href="{{ $url }}"
                           target="_blank"
                           class="btn-pdf"
                           title="Abrir / descargar PDF">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>

                    @else
                        {{-- Otro tipo de archivo --}}
                        <a href="{{ $url }}" target="_blank" class="btn-pdf">
                            <i class="fas fa-paperclip"></i> Ver
                        </a>
                    @endif

                @else
                    <span class="sin-doc">Sin archivo</span>
                @endif
            </td>

        </tr>
    @endforeach
    </tbody>
</table>

{{-- ── Modal para ver imagen en grande ──────────────────────────────── --}}
<div class="modal fade" id="modalImagen" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="background: #1a1a1a; border: none;">
            <div class="modal-header" style="border:none; padding: 8px 12px;">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff; opacity:1;">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="imagenModal" src="" alt="Captura">
            </div>
        </div>
    </div>
</div>

<script>
    function verImagen(url) {
        document.getElementById('imagenModal').src = url;
        $('#modalImagen').modal('show');
    }
</script>
