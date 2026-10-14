<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

/**
 * Controlador base para los módulos CRUD (Crear, Leer, Actualizar, Eliminar).
 * Cada módulo hereda de esta clase y solo declara su modelo, columnas y campos.
 */
abstract class CrudController extends Controller
{
    /** Clase del modelo Eloquent (por ejemplo, Membresia::class). */
    abstract protected function modelo(): string;

    /** Prefijo de las rutas (por ejemplo, 'membresias'). */
    abstract protected function ruta(): string;

    /** Título del módulo. */
    abstract protected function titulo(): string;

    /** Columnas que se muestran en el listado. */
    abstract protected function columnas(): array;

    /** Campos del formulario con sus reglas de validación. */
    abstract protected function campos(?Model $registro): array;

    /** Relaciones a cargar en el listado (evita consultas repetidas). */
    protected function relaciones(): array
    {
        return [];
    }

    /** Ajusta los datos validados antes de guardarlos. */
    protected function preparar(array $datos, ?Model $registro): array
    {
        return $datos;
    }

    /** Devuelve un texto si el registro no se puede eliminar. */
    protected function motivoNoEliminar(Model $registro): ?string
    {
        return null;
    }

    private function cfg(): array
    {
        return [
            'titulo' => $this->titulo(),
            'ruta' => $this->ruta(),
            'columnas' => $this->columnas(),
        ];
    }

    private function reglas(array $campos): array
    {
        $reglas = [];
        foreach ($campos as $campo) {
            if (isset($campo['rules'])) {
                $reglas[$campo['name']] = $campo['rules'];
            }
        }
        return $reglas;
    }

    private function etiquetas(array $campos): array
    {
        $etiquetas = [];
        foreach ($campos as $campo) {
            $etiquetas[$campo['name']] = mb_strtolower($campo['label']);
        }
        return $etiquetas;
    }

    private function mensajes(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'email' => 'El campo :attribute debe ser un correo válido.',
            'numeric' => 'El campo :attribute debe ser un número.',
            'integer' => 'El campo :attribute debe ser un número entero.',
            'date' => 'El campo :attribute debe ser una fecha válida.',
            'unique' => 'Ya existe un registro con ese :attribute.',
            'exists' => 'El valor seleccionado en :attribute no es válido.',
            'in' => 'El valor seleccionado en :attribute no es válido.',
            'min' => 'El campo :attribute no cumple el mínimo permitido (:min).',
            'max' => 'El campo :attribute supera el máximo permitido (:max).',
            'after_or_equal' => 'El campo :attribute debe ser igual o posterior a :date.',
        ];
    }

    // ---- Acciones CRUD ----------------------------------------------------

    public function index()
    {
        $clase = $this->modelo();
        $llave = (new $clase())->getKeyName();

        $filas = $clase::with($this->relaciones())
            ->orderByDesc($llave)
            ->paginate(10);

        return view('crud.index', ['cfg' => $this->cfg(), 'filas' => $filas]);
    }

    public function create()
    {
        return view('crud.form', [
            'cfg' => $this->cfg(),
            'registro' => null,
            'campos' => $this->campos(null),
            'url' => route($this->ruta() . '.store'),
            'metodo' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $campos = $this->campos(null);
        $datos = $request->validate($this->reglas($campos), $this->mensajes(), $this->etiquetas($campos));

        $clase = $this->modelo();
        $clase::create($this->preparar($datos, null));

        return redirect()->route($this->ruta() . '.index')->with('ok', 'Registro creado correctamente.');
    }

    public function edit($id)
    {
        $clase = $this->modelo();
        $registro = $clase::findOrFail($id);

        return view('crud.form', [
            'cfg' => $this->cfg(),
            'registro' => $registro,
            'campos' => $this->campos($registro),
            'url' => route($this->ruta() . '.update', $id),
            'metodo' => 'PUT',
        ]);
    }

    public function update(Request $request, $id)
    {
        $clase = $this->modelo();
        $registro = $clase::findOrFail($id);

        $campos = $this->campos($registro);
        $datos = $request->validate($this->reglas($campos), $this->mensajes(), $this->etiquetas($campos));

        $registro->update($this->preparar($datos, $registro));

        return redirect()->route($this->ruta() . '.index')->with('ok', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $clase = $this->modelo();
        $registro = $clase::findOrFail($id);

        if ($motivo = $this->motivoNoEliminar($registro)) {
            return redirect()->route($this->ruta() . '.index')->with('error', $motivo);
        }

        try {
            $registro->delete();
        } catch (QueryException $e) {
            return redirect()->route($this->ruta() . '.index')
                ->with('error', 'No se puede eliminar: el registro está relacionado con otros datos.');
        }

        return redirect()->route($this->ruta() . '.index')->with('ok', 'Registro eliminado correctamente.');
    }
}
