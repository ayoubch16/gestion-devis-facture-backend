<?php
// app/Http/Controllers/ArticleController.php
namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
         return response()->json(Article::orderBy('id', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unite' => 'nullable|string',
            'categoryId' => 'required|numeric',
            'nameArticle' => 'required|string',
            'descriptionArticle' => 'nullable|string',  // Changé à nullable
            'priceArticle' => 'required|numeric',
        ]);

        return Article::create($validated);
    }

    public function show(Article $article)
    {
        return $article->load('categoryId');
    }

    public function update(Request $request, $id)
    {
        $article = Article::find($id);
        
        if (!$article) {
            return response()->json(['success' => false, 'message' => 'Article non trouvé'], 404);
        }

        $validated = $request->validate([
            'unite' => 'nullable|string',
            'categoryId' => 'sometimes|required|numeric',  // Ajouté 'required' avec 'sometimes'
            'nameArticle' => 'sometimes|required|string',  // Ajouté 'required' avec 'sometimes'
            'descriptionArticle' => 'nullable|string',
            'priceArticle' => 'sometimes|required|numeric',  // Ajouté 'required' avec 'sometimes'
        ]);

        $article->update($validated);
        
        return $article;
    }

    public function destroy($id)
    {
        $article = Article::find($id);

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Article non trouvé.',
                'errorCode' => 'ARTICLE_NOT_FOUND'
            ], 404);
        }

        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article supprimé avec succès.'
        ]);
    }
}