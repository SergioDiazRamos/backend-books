<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function podemos_obtener_todos_los_libros()
    {
        $this->withoutExceptionHandling();

        $books = Book::factory()->count(2)->create();

        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                "title" => $books[0]->title,
            ])->assertJsonFragment([
                "title" => $books[1]->title,
            ]);
    }

    /** @test */
    public function podemos_obtener_un_libro_especifico()
    {
        $this->withoutExceptionHandling();

        $book = Book::factory()->create();

        $this->getJson(route('books.show', $book))
            ->assertJsonFragment([
                "title" => $book->title
            ]);
    }

    /** @test */
    public function podemos_crear_libros()
    {
        $this->withoutExceptionHandling();

        $this->postJson(route('books.store'), [
            "title" => "Cien años de soledad"
        ])->assertJsonFragment([
            "title" => "Cien años de soledad"
        ]);

        $this->assertDatabaseHas('books', [
            "title" => "Cien años de soledad"
        ]);
    }

    /** @test */
    public function titulo_es_obligatorio_al_crear_libro()
    {
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

    }

    /** @test */
    public function podemos_actualizar_libros()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [
            "title" => "Cien años de soledad"
        ])->assertJsonFragment([
            "title" => "Cien años de soledad"
        ]);

        $this->assertDatabaseHas('books', [
            "title" => "Cien años de soledad"
        ]);
    }

    /** @test */
    public function titulo_es_obligatorio_al_editar_libro()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

    }

    /** @test */
    public function podemos_eliminar_un_libro()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }


}
