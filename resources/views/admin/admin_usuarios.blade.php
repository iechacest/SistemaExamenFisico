<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root {
            --celeste: #00bcd4;
            --celeste-suave: #e0f7ff;
            --negro: #111111;
            --gris-texto: #555555;
        }

        * { box-sizing: border-box; margin:0; padding:0; font-family: system-ui; }

        body { background: linear-gradient(135deg, var(--celeste-suave), #fff); }

        .layout { max-width:1180px; margin:0 auto; padding:2rem 1rem; }

        .card {
            background:white;
            border-radius:14px;
            box-shadow:0 6px 18px rgba(0,0,0,0.06);
            padding:1.2rem;
            border:1px solid rgba(0,188,212,0.18);
        }

        table { width:100%; border-collapse: collapse; margin-top:0.7rem; }
        thead { background: var(--celeste-suave); }
        th, td {
            padding:0.55rem 0.4rem;
            border-bottom:1px solid #eee;
        }

        tbody tr:hover { background:#e0f7ff; }

        .btn {
            border:none;
            border-radius:999px;
            padding:0.35rem 1rem;
            font-size:0.78rem;
            font-weight:600;
            cursor:pointer;
            text-decoration:none;
            display:inline-block;
        }

        .btn-primary { background:var(--celeste); color:white; }
        .btn-warning { background:#ffc107; color:black; }
        .btn-danger { background:#ff5252; color:white; }
        .btn-secondary { background:#607d8b; color:white; }

        .titulo { font-size:1.25rem; font-weight:800; margin-bottom:1rem; }
    </style>
</head>

<body>
<div class="layout">

    <a href="{{ route('dash.admin') }}" class="btn btn-secondary">← Volver al panel</a>

    <div class="titulo">Administración de Usuarios</div>

    <a href="{{ route('admin.usuarios.crear') }}" class="btn btn-primary">
        + Crear nuevo usuario
    </a>

    <div class="card" style="margin-top:1rem;">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombres</th>
                    <th>Cargo</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
            @foreach($usuarios as $i => $u)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $u->nombres }} {{ $u->apellido_pat }} {{ $u->apellido_mat }}</td>
                    <td>
                        {{ $u->cargo == 0 ? 'Administrador' : 'Registrador' }}
                    </td>

                    <td>
                        <a href="{{ route('admin.usuarios.editar', $u->id_usu) }}" 
   class="btn btn-warning">
    Editar
</a>

<form action="{{ route('admin.usuarios.eliminar', $u->id_usu) }}" method="POST" style="display:inline;">


                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger"
                                    onclick="return confirm('¿Eliminar este usuario?')">
                                Eliminar
                            </button>
                        </form>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
