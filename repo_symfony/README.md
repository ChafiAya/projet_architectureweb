# projet_architectureweb


## Gestion de disponibilité des salles en temp reel (dispo ou non)
on a travailler avec `Symfony Messenger`, et pour cela on doit lancer le `worker Symfony Messenger` avec la commande suivante:
``` symfony console messenger:consume async --time-limit=3600```