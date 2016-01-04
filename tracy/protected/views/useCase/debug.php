 
<?php
/* @var $this UseCaseController */
/* @var $model UseCase */

$this->breadcrumbs=array(
    'Use Cases'=>array('index'),
    "LaTeX",
);

$this->menu=array(
    array('label'=>'List Sources', 'url'=>array('source/index')),
    array('label'=>'Create ExternalSource', 'url'=>array('externalSource/create')),
    array('label'=>'Create Use Case', 'url'=>array('useCase/create')),
    array('label'=>'List UseCases', 'url'=>array('useCase/index')),
    );
?>

<h1>Debug</h1>

<?php

$sourceArray = array();

foreach (UseCase::model()->findAll() as $useCase) {
  echo "<p>Calcolo sorgente di ".$useCase->id_use_case."";
  $string = '';
  $number = UseCaseController::generateNumber($useCase);
  echo " che è il numero $number</p>";
  $actors = UseCaseController::getActors($useCase);
  $string .="\\subsection{UseCase}";
  $string .=$number;
  $string .="
  \\begin{figure}[H]
    \\centering  
    \\includegraphics[scale=0.95]{img/ucsample.pdf}
    \\caption{Caso d'uso $number (UC$number)}\\label{fig:uc_$number} 
  \\end{figure}";
  $string .="
  \\begin{itemize}
    \\item \\textbf{Attori}: $actors;
    \\item \\textbf{Scopo e descrizione}: {$useCase->description}; 
    \\item \\textbf{Pre-condizioni}: {$useCase->pre};
    \\item \\textbf{Flusso principale degli eventi}:
      \\begin{enumerate}
";
  foreach (UseCaseEvent::model()->findAll("use_case = {$useCase->id_use_case} AND category = 1") as $event) {
    
     //TODO reorder results by 'order' field
     
     $string .="          \\item {$event->description}";
     if ($event->refers_to) {
       $number = $this->generateNumber(UseCase::model()->findByPk($event->refers_to));
       $string .=" (UC$number)";
     }
    echo "
";
  }
  $string .="\n      \\end{enumerate}
";
  if (UseCaseController::hasAlternate($useCase)) {
  $string .="    \\item \\textbf{Scenari Alternativi}:
      \\begin{enumerate}
";
  foreach (UseCaseEvent::model()->findAll("use_case = {$useCase->id_use_case} AND category = 2") as $event) {
    
     //TODO reorder results by 'order' field
     
     $string .="          \\item {$event->description}";
     if ($event->refers_to) {
       $number = UseCaseController::generateNumber(UseCase::model()->findByPk($event->refers_to));
       $string .="(UC$number)";
     }
    echo "
";
  }
  $string .="\n      \\end{enumerate}
";
 }
  $string .="    \\item \\textbf{Post-condizioni}: {$useCase->post};
";
  $string .="  \\end{itemize}";
  $sourceArray[UseCaseController::generateNumber($useCase)] = $string;
}

foreach ($sourceArray as $key => $source) {
  echo "<pre>$source</pre>";
}


?> 