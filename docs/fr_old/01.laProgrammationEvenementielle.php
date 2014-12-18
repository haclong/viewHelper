La programmation événementielle

S’il me paraît plus aisé de faire des applications en programmation séquentielle, il existe en opposition à 
celle ci des applications basées sur une programmation événementielle. Même si PHP ne semble pas être un langage dans 
lequel on peut naturellement faire de la programmation événementielle, nous verrons en quoi le framework Zend 
Framework 2 a mis en place des composants pour nous aider à réaliser notre projet.

Séquentielle vs Evénementielle ?

La programmation séquentielle (http://fr.wikipedia.org/wiki/Programmation_s%C3%A9quentielle) est ce que je 
considère la façon la plus basique pour aborder la programmation. En programmation séquentielle, en gros, on 
définit une liste d’instructions ordonnées et le programme est sensé exécuter ces instructions toujours dans 
le même ordre. Lorsque vous apprenez à développer et qu’on vous recommande de dessiner un organigramme, 
http://upload.wikimedia.org/wikipedia/commons/thumb/2/29/Pstricks_exo_organigramme_simple.png/220px-
Pstricks_exo_organigramme_simple.png
vous appliquez très exactement une logique qui va vous faire programmer en séquentiel.

La programmation événementielle (http://fr.wikipedia.org/wiki/Programmation_%C3%A9v%C3%A9nementielle) est une 
façon d’aborder votre programme de façon différente. On ne code plus à partir d’une liste d’instructions 
ordonnées mais on commence en déterminant une liste d’événements. Votre application est exécutée et 'il se 
passe quelquechose' quand un événement de la liste survient. Dit comme ça, on peut se dire qu’en programmation 
séquentielle aussi, 'il se passe quelquechose' quand un événement survient : quand je clique sur le bouton, il 
se passe quelquechose. Mais entre les deux, la différence survient dans la façon d’écrire le code. Non pas 
dans ce qu’il se passe.

En séquentiel, vous serez tenu d’écrire :
if (bouton_clicked())
{
    prog do_something() ;
}
Cela implique - au niveau de l’architecture de votre application, dans le bout de code qui dit 
(bouton_clicked()), vous allez devoir charger / connaitre / lister les autres éléments qui vont réagir au 
moment où le bouton a été cliqué - cela veut dire gestion des dépendances.

En événementiel, en principe, vous n’êtes pas tenu de l’écrire.
Vous aurez votre bouton d’un côté qui va dire
// bouton
trigger (oh ! i’ve been clicked !) ;

et de l’autre côté, un élément qui va faire quelquechose parce que le bouton a été cliqué.
// prog
function do_something()
{
    if (bouton: oh! i’ve been clicked !)
    {
          return something done ;
    }
}
L’élément qui réagit n’a pas besoin de savoir finalement QUI est le bouton qui a été cliqué et le bouton, 
quant à lui, n’a pas à savoir qui fait quoi au moment où il a été cliqué… En terme de dépendances, on est plus 
que ok. Par contre, vous aurez besoin de rigueur… il ne faudra pas éparpiller vos listeners (les éléments qui 
réagissent aux événements) et les éléments qui génèrent les événements parce que pour identifier ce qui les 
lie est plus compliqué.

Foncièrement, que vous choisissez l’un ou l’autre, votre programme fera la même chose (parce que, après tout, 
c’est ce qu’on attend de vous, que vous développiez un programme qui fait ce qu’on veut qu’il fasse). En web, 
je pense que c’est surtout le schéma séquentiel qui prime puisque tout le web est organisé sur un principe 
d’échange de requêtes et de réponses (si on fait ça, faire ça, si on fait ci, faire ci)... Mais en jeux par 
exemple, une programmation événementielle serait sûrement plus adaptée.

Le projet

J’ai découvert la programmation événementielle en cherchant totalement autre chose, merci Internet. Je voulais 
avec des détails sur le principe de séparation commande-requête (http://fr.wikipedia.org/wiki/Transparence_r
%C3%A9f%C3%A9rentielle#Principe_de_s.C3.A9paration_commande-requ.C3.AAte) ou CQRS 
(http://en.wikipedia.org/wiki/Command%E2%80%93query_separation#Command_Query_Responsibility_Segregation) en 
anglais. Alors que le tutoriel me vantait les bienfaits de ce principe, je me suis retrouvé au milieu d’une 
nouvelle façon d’aborder le code. Le tutoriel original (http://www.cqrs.nu/tutorial/cs/01-design) est destiné 
au langage .Net. On va reprendre l’argument du tutoriel et tenter de monter la même application pour PHP.

Le scenario

Nous développons une application pour un petit café. Nous allons nous concentrer sur le concept d’une note. 
Celle ci va nous permettre de suivre les clients (individuels ou groupes) qui se présentent au café. Quand les 
clients arrivent au café et prennent une table, une note est ouverte. Les clients peuvent alors commander des 
boissons ou des plats. Les boissons sont servies immédiatement mais pour les plats, il faut attendre que les 
plats soient préparés. Une fois que les plats sont prêts, ils peuvent alors être servis.
Durant tout le temps que les clients sont au café, ils peuvent commander de nouvelles boissons, ou de nouveaux 
plats. Ils peuvent annuler une boisson ou un plat uniquement si le plat (ou la boisson) n’a pas encore été 
préparé ou servi.
A la fin, la note est fermée quand le client la règle. Une note ne peut être réglée si tous les plats et 
toutes les boissons n’ont pas été soit servis, soit annulés. Une note doit être réglée en totalité. Pas de 
crédit. Le client peut également ajouter un pourboire.

Les événements

En programmation séquentielle, je vous vois déjà frétiller, vous créez une entité client, une entité boisson, 
une entité plat etc… pour vos boissons et plats, vous avez nécessairement un système de statuts qui aura les 
valeurs suivantes : commandé, préparé, servi… Si la boisson est commandée, le statut passe à commandé. Si la 
boisson est servie, le statut passe à servi. etc… On l’a tous fait.

En programmation événementielle, les objets ne nous intéressent pas pour le moment. Ce qui nous intéresse ici, 
c’est ce qu’il se passe. Et chaque fois qu’il se passe quelquechose, c’est un évenements dans notre 
application. Et rappelons nous : un événement est toujours un truc qui s’est passé (dans le passé)...

Voyons notre scénario cette fois avec du code :
Quand les clients arrivent au café et prennent une table, une note est ouverte.
-> une Opération : OpenTab
-> un Evénement : TabOpened
-> une Exception : TableNumberUnavailable (la table est déjà occupée, on ne peut pas ouvrir une seconde note 
tant que la note précédente n’est pas fermée)

Les clients peuvent alors commander des boissons ou des plats. 
-> une Opération : PlaceOrder
-> un Evénement : DrinksOrdered
-> un Evenement : FoodOrdered
-> une Exception : TabNotOpened

Les boissons sont servies immédiatement 
-> une Opération : MarkDrinksServed
-> un Evénement : DrinksServed

mais pour les plats, il faut attendre que les plats soient préparés. 
-> une Opération : MarkFoodPrepared
-> un Evénement : FoodPrepared

Une fois que les plats sont prêts, ils peuvent alors être servis.
-> une Opération : MarkFoodServed
-> un Evénement : FoodServed

Durant tout le temps que les clients sont au café, ils peuvent commander de nouvelles boissons, ou de nouveaux 
plats. (PlaceOrder)
Ils peuvent annuler une boisson ou un plat uniquement si le plat (ou la boisson) n’a pas encore été préparé ou 
servi.
-> une Operation : AmendOrder
-> une Opération : MarkFoodCancelled
-> une Opération : MarkDrinksCancelled
-> un Evénement : FoodCancelled
-> un Evenement : DrinksCancelled
-> une Exception : CannotCancelledServedItem
-> une Exception : CannotCancelledPreparedItem

A la fin, la note est fermée quand le client la règle. Une note ne peut être réglée si tous les plats et 
toutes les boissons n’ont pas été soit servis, soit annulés. Une note doit être réglée en totalité. Pas de 
crédit. Le client peut également ajouter un pourboire.
-> une Opération : CloseTab
-> un Evénement : TabClosed
-> une Exception : TabHasUnservedItem
-> une Exception : MustPayEnough

Voila
Le tutoriel d'origine insiste bien sur le fait que pour rester au plus près du business, il faut garder les entités très parlantes. Il n'y a pas de raccourcis : il faut une opération OpenTab pour avoir un événement TabOpened. Reférez vous au tutoriel d'origine, sur cette page http://cqrs.nu/tutorial/cs/02-domain-logic où tout le mécanisme logique est décrit. La conception inclus des tests de comportement Behaviour Driven Tests. Je n'ai pas réussi à installer Behat pour monter les mêmes tests mais lorsqu'on lit les scénarii de tests du tutoriel, la logique Opération/Evénement prend tout son sens.

N’hésitez pas à faire des événements clairs et précis. Evitez les banalités du genre “Created”, “Updated” etc… parce que tous vos évenements finiront par tous se ressembler.

On peut très bien nommer ses événements en français. Par habitude, les miens sont toujours en anglais. Pour des tas de raisons différentes pas toujours justifiées peut être mais le pli est pris.
