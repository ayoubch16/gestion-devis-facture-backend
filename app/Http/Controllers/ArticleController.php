<?php
// app/Http/Controllers/ArticleController.php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\CategoryArticle;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        return Article::with('category')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unite' => 'required|string',
            'category_article_id' => 'required|exists:category_articles,id',
            'name_article' => 'required|string',
            'description_article' => 'required|string',
            'price_article' => 'required|numeric',
        ]);

        return Article::create($validated);
    }

    public function show(Article $article)
    {
        return $article->load('category');
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'unite' => 'string',
            'category_article_id' => 'exists:category_articles,id',
            'name_article' => 'string',
            'description_article' => 'string',
            'price_article' => 'numeric',
        ]);

        $article->update($validated);
        return $article;
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return response()->noContent();
    }

    public function categories()
    {
        return CategoryArticle::all();
    }
}