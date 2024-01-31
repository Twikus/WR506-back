<?php

namespace App\Controller;

use App\Entity\MediaObject;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MediaObjectController extends AbstractController
{
    public function __invoke(Request $request, MovieRepository $repo): MediaObject
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('fichier vide');
        }

        $mediaObject = new MediaObject();
        $mediaObject->file = $uploadedFile;

        // Récupération de l'ID du film à partir de la requête
        $movieData = json_decode($request->get('movie'), true);
        $movieId = str_replace('/api/movies/', '', $movieData['@id']);

        // Récupération de l'objet Movie correspondant à partir de la base de données
        $movie = $repo->find($movieId);

        if (!$movie) {
            throw new BadRequestHttpException('Film non trouvé');
        }

        // Liaison de l'objet Movie à l'objet MediaObject
        $mediaObject->setMovie($movie);

        return $mediaObject;
    }
}