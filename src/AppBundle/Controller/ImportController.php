<?php

namespace AppBundle\Controller;

use AppBundle\Service\XmlImportService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImportController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function uploadAction(Request $request)
    {
        $builder = $this->createFormBuilder();
        $builder
            ->add('attachment', FileType::class, ['label' => false, 'attr' => ['style' => 'display:none']])
        ;
        $form = $builder->getForm();
        $form->handleRequest($request);
        $result = null;
        if ($form->isValid()) {
            /** @var UploadedFile $attachment */
            $attachment = $form['attachment']->getData();
            if($attachment->getMimeType() !== 'application/xml'){
                $form->addError(new FormError('Invalid file format'));
            } else {
                try{
                    $result = $this->getXmlImportService()->load($attachment->getRealPath());
                } catch (\ParseError $e) {
                    $form->addError(new FormError($e->getMessage()));
                }
            }
        }

        return $this->render('AppBundle::upload.html.twig', [
            'form'   => $form->createView(),
            'result' => $result,
        ]);
    }

    /**
     * @return XmlImportService
     */
    protected function getXmlImportService()
    {
        return $this->container->get('app.xml_import_service');
    }
}
