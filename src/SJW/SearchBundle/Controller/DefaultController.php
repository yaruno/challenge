<?php

namespace SJW\SearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     *
     * @Template("SJWSearchBundle:Default:index.html.twig")
     */

    public function indexAction()
    {

        return array();
    }

    /**
     * @Route("/api/search", name="search")
     *
     * @Template("SJWSearchBundle:Default:index.html.twig")
     */
    public function searchAction(Request $request) {
        // Get the search string from the UI.
        $searchString = $request->query->get('q');

        // Read resource file.
        $kernel = $this->get('kernel');

        $filePath = $kernel->locateResource('@SJWSearchBundle/Resources/data/numbers_lat_lon.txt');

        // Split lines and comma delimited values.
        $lines = explode("\n", file_get_contents($filePath, true));

        $numbers = array();
        
        // TODO: Implement search based on query string.

        $results =[];

        $nearest_N = 10;

        foreach($lines as $line) {
                $temp = explode(';', $line);
                if(count($temp) == 5){
                    $unit = array("postcode"=>$temp[0], "city"=>$temp[1], "pop"=>$temp[2],"lat"=>$temp[3],"lon"=>$temp[4]);
                }
                $numbers[] = $unit;
        }


        if (is_numeric($searchString)){
                foreach($numbers as $number){
                    if ($number['postcode'] == $searchString){
                        $results = $number;
                    }
                }
            }

            if (is_string($searchString)){
                foreach($numbers as $number){
                    if ($number['city'] == $searchString){
                        $results = $number;
                    }
                }
            }
           
            $wanted_results = [];


            if (!empty($results)){


                foreach ($numbers as $key => $row) {
                    $postcode[$key]  = $row['postcode'];
                    $city[$key] = $row['city'];
                    $pop[$key] = $row['pop'];
                }
                // Sort the data with population ascending
                array_multisort($pop, SORT_ASC, $numbers);
               
                $key = array_search($results,$numbers);
               
                //check if key + N numbers is beyond array range, adjust accordignly

                if ($key-5 <0 ) {


                    for ($i=$key; $i<=$key+10; $i++){
                        //var_dump($numbers[$i]);
                        $wanted_results[] = $numbers[$i];
                        //$var_dump = $wanted_results;

                    }


                }

                elseif ($key+5 > count($numbers)) {

                    for ($i=$key-10; $i<=$key; $i++){
                        //var_dump($numbers[$i]);
                        $wanted_results[] = $numbers[$i];
                        //$var_dump = $wanted_results;

                    }



                }

                else{

                    for ($i=$key-5; $i<=$key+5; $i++){
                       
                        $wanted_results[] = $numbers[$i];
                    

                    }

                }
               

            }   




        
       
        // Output content.
        return new JsonResponse($wanted_results);
     
    }
}
