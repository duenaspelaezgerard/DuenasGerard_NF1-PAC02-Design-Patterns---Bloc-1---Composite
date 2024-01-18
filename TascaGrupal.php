<?php

abstract class AbstractTask {
    //variables 
    
    private $title;
    private $date;
    private $dueDate;
    private $assignedTo;
    private $description;
    

    

    public function setTitle($title){
        $this->title = $title;
    }

    public function getTitle(){
        return $this->title;
    }

    public function setDate($date){
        $this->date = $date;
    }

    public function getDate(){
        return $this->date;
    }

    public function setDueDate($dueDate){
        $this->dueDate = $dueDate;
    }

    public function getDueDate(){
        return $this->dueDate;
    }

    public function setAssignedTo($assignedTo){
        $this->assignedTo = $assignedTo;
    }

    public function getAssignedTo(){
        return $this->assignedTo;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function getDescription(){
        echo "- Tarea: " . $this->getTitle();
        echo "<ul>";
        echo "<li>Fecha: " . $this->getDate();
        echo "</li>";
        echo "<li>Fecha entrega: " . $this->getDueDate();
        echo "</li>";
        echo "<li>Asignado: " . $this->getAssignedTo();
        echo "</li>";
        
        
        
    }
}

class Project extends AbstractTask{
    private $budget;
    private $Workitems = array();

    public function add(AbstractTask $Workitem){
        array_push($this->Workitems, $Workitem);
    }
    public function remove(AbstractTask $Workitem){
        array_pop($this->Workitems);
    }
    
    public function setBudget($budget){
        $this->budget = $budget;
    }
    
    public function getBudget(){
        return $this->budget;
    }

    public function hasChildren() {
        return (bool)(count($this->Workitems) > 0);
    }
        
    public function getChild($i) {
        return $this->Workitems[$i];
    }

    public function getDescription(){
        echo "<li>budget: " . $this->getBudget();
        echo "</li>";
        if ($this->hasChildren()){
            foreach($this->Workitems as $Workitem){
                    
                echo"</ul>";
            }
        }else{
            echo"</ul>";
        }
    }
}

class TimeBasedTask extends AbstractTask {
    private $estimatedHours;
    private $hoursSpent;
    private $ChildTasks = array();

    public function add(AbstractTask $childTask){
        array_push($this->ChildTasks, $childTask);
    }
    public function remove(AbstractTask $childTask){
        array_pop($this->ChildTasks);
    }

    
    public function setEstimatedHours($estimatedHours){
        $this->estimatedHours = $estimatedHours;
    }

    public function getEstimatedHours(){
        return $this->estimatedHours;
    }

    public function setHoursSpent($hoursSpent){
        $this->hoursSpent = $hoursSpent;
    }

    public function getHoursSpent(){
        return $this->hoursSpent;
    }


    function __construct($title, $date, $dueDate, $assignedTo, $description, $hoursEstimated, $hoursSpent, $childTask = null){
        parent::setTitle($title);
        parent::setDate($date);
        parent::setDueDate($dueDate);
        parent::setAssignedTo($assignedTo);
        parent::setDescription($description);
        $this->setEstimatedHours($hoursEstimated);
        $this->setHoursSpent($hoursSpent);
    }
    public function getDescription(){

        echo "<li>Horas estimadas: " . $this->getEstimatedHours() . "</li>";
        echo "<li>Horas totales: " . $this->getHoursSpent() . "</li>";

        
        
    }

}
class FixedBudgetTask extends AbstractTask{
    private $budget;
    private $ChildTasks = array();

    public function add(TimeBasedTask $childTask){
        array_push($this->ChildTasks, $childTask);
    }
    public function remove(TimeBasedTask $childTask){
        array_pop($this->ChildTasks);
    }

    public function hasChildren() {
        return (bool)(count($this->ChildTasks) > 0);
    }
        
    public function getChild($i) {
        return $this->ChildTasks[$i];
    }

    public function __construct($title, $budget){
        parent::setTitle($title);
        $this->budget = $budget;
    }

    public function getBudget(){
        return $this->budget;
    }

    public function getDescription(){

        echo "<li>Horas estimadas: " . $this->getBudget() . "</li>";

        if ($this->hasChildren()){
            foreach($this->ChildTasks as $childTask){
                $childTask->getDescription();    
                echo"</ul>";
            }
        }else{
            echo"</ul>";
        }
        
    }
    
}

$project = new Project();
$project->setTitle("Proyecto Principal");

$timeBasedTask = new TimeBasedTask("Tarea con Tiempo", "2024-01-11", "2024-01-18", "Usuario", "Descripción", 10, 5);
$fixedBudgetTask = new FixedBudgetTask("Tarea con Presupuesto", 1000);
$subTask = new TimeBasedTask("Subtarea", "2024-01-12", "2024-01-15", "Usuario", "Descripción Subtarea", 8, 3);

$timeBasedTask2 = new TimeBasedTask("Tarea con Tiempo2", "2024-01-11", "2024-01-18", "Usuario", "Descripción", 10, 5);
$fixedBudgetTask2 = new FixedBudgetTask("Tarea con Presupuesto2", 1000);
$subTask2 = new TimeBasedTask("Subtarea2", "2024-01-12", "2024-01-15", "Usuario", "Descripción Subtarea", 8, 3);

$timeBasedTask->add($subTask);
$project->add($timeBasedTask);
$project->add($fixedBudgetTask);

$timeBasedTask2->add($subTask2);
$project->add($timeBasedTask2);
$project->add($fixedBudgetTask2);

// Mostrar la lista de tareas
echo "Lista de tareas:<br>";
$project->getDescription();


?>
