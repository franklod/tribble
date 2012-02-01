<?php
class Fakedata_model extends CI_Model
{

  function __construct()
  {
    // Call the Model constructor
    parent::__construct();
  }

  function users($num_users)
  {

    $users = $this->random_names($num_users);
   
    $user_hashes = array();
    
    $i = 0;
    
    foreach($users as $user){
      
      $this->user_email = $user['email'];
      $this->user_password = $this->encrypt->sha1($this->encrypt->sha1($user['email']));
      $this->user_realname = $user['name'];
      $this->user_bio = '';
      
      // check if the email already exists
      $query = $this->db->get_where('user', array('user_email' => $this->user_email));
      
      if ($query->num_rows() > 0){
        // user exists: return error message
        $result->error = "We already have that email on our database?";
      } else {
        // user is new: write to db and return uid
        if(!$this->db->insert('user',$this)){
          // error while writing to db: return error message
          $result->error = "We're sorry but the database dwarf made a mistake. The dungeon master has been notified";          
        
        } else {
          // everything went well. commit the transaction and return the uid hash
          $result->user_hash = do_hash($this->user_email);
          //var_dump($result);     
        }
      }
      $user_hashes[$i] = $result->user_hash;
      $i++;
    }
    $result->users = $user_hashes;    
    return $result;                                
  }
  
  function posts($num){
    
    $posts = $this->random_titles($num);
    $images = $this->random_images($num);
    
    $this->db->select('user_id');
    $this->db->distinct();
    $this->db->from('user');
    $this->db->order_by('user_id','random');
    $this->db->limit($num);
    
    $result = $this->db->get();
    $users = $result->result_array();
    
    for($i=0;$i<$num;$i++){
      $user_id = $users[$i]['user_id'];
      $post_text = $posts[$i];
      $post_title = $posts[$i];
      $post_tags = str_replace(' ',',',$posts[$i]);
      $image = $images[$i];
      $imagedata['image_palette'] = json_encode(getImageColorPalette($this->config->item('app_path').'/data/test/'.$image));      
      
      $data = array(
         'post_text' => $post_text,
         'post_title' => $post_title,
         'post_user_id' => $user_id
      );
      //var_dump($data);
//      var_dump($post_tags);
//      var_dump($image);
      
      $this->db->trans_begin();
      if(!$this->db->insert('post', $data)){
         $result->error = 'Error while writing tribble data.';
         var_dump($result);
      }
      
      $post_id = $this->db->insert_id();
      
      $tagdata['tag_content'] = $post_tags;
      $tagdata['tag_post_id'] = $post_id;
        
      if(!$this->db->insert('tag',$tagdata)){
        $result->error = 'Error while writing tag data.';
        var_dump($result);
      }
      
      $imagedata['image_post_id'] = $post_id;
      $imagedata['image_path'] = '/data/tests/'.$image;      
      
      if(!$this->db->insert('image',$imagedata)){
        $result->error = 'Error while writing image data.';
        var_dump($result);  
      }            
      $likedata['like_post_id'] = $post_id;
      $likedata['like_user_id'] = $user_id;
            
      if(!$this->db->insert('like',$likedata)){
        $result->error = "Error while writing like data";
        var_dump($result);
      }
      $this->db->trans_complete();
      echo "Post (".$post_id.") was inserted<br>";
    }                                      
  }
  
  function random_images($num){
    $imagesfiles = array('4.png','5.png','7.png','8.png','9.png','12.png','13.png','15.png','16.png','17.png','21.png','1.jpg','2.jpg','3.jpg','6.jpg','10.jpg','11.jpg','14.jpg','18.jpg','19.jpg','20.jpg','23.jpg');
    $images = array();
    for($i=0;$i<$num;$i++){
      $image = $imagesfiles[rand(0,count($imagesfiles)-1)];
      $images[$i] = $image;      
    }
    return $images;
  }
  
  function random_titles($num){
    $jargon = array('user interface','ux','ui','navigation','button','menu','dropdown','poster','composition','layout','illustration','logo');
    $adjective = array('graphic','typographic','interactive','inovative','beautiful','shiny','glossy','wide','elastic','layered','metallic','funny','adorable','beautiful','clean','drab','elegant','fancy','glamorous','handsome','long','magnificent','old-fashioned','plain','quaint','sparkling','ugliest','unsightly','wide-eyed','ancient','brief','early','fast','late','long','modern','old','old-fashioned','quick','rapid','short','slow','swift','young');
    $media = array('web','android','iPhone','browser','iPad','tablet','magazine','book cover');    
    $titles = array();    
    for($i=0;$i<$num;$i++){
      $title = $adjective[rand(0,count($adjective)-1)] . ' ' . $media[rand(0,count($media)-1)] . ' ' . $jargon[rand(0,count($jargon)-1)];
      $titles[$i] = $title;
    }
    return $titles;
  }
  
  function random_names($num){
    $first_names = array('Aarão','Abel','Abílio','Abigail','Abraão','Acacio','Adalberto','Adão','Adelaide','Adélia','Adélio','Adelina','Adelino','Adérito','Adolfo','Adosindo','Adriana','Adriano','Afonso','Ágata','Agostinho','Aguinaldo','Aida','Aires','Alarico','Alberto','Alberta','Albino','Alcides','Alceste','Alda','Aldo','Aldonça','Aleixo','Alexandra','Alexandre','Alfredo','Alice','Alicia','Aline','Alípio','Almeno','Almerinda','Almor','Aluísio','Álvaro','Alvito','Alzira','Amadeu','Amália','Amanda','Amandio','Amélia','Américo','Amílcar','Ana','Anabela','Anacleto','André','Andreia','Ângela','Ângelo','Angélica','Angélico','Andreoleto','Angelina','Angelino','Aniano','Aniana','Aníbal','Anind','Anita','Anna','Anselmo','Antão','Antero','Antónia','Antonieta','António','Arcidres','Armanda','Armando','Arminda','Armindo','Arnaldo','Artur','Asi','Astolfo','Átila','Augusta','Augusto','Aurélia','Aurélio','Aurora','Balduíno','Baltasar','Bárbara','Barnabé','Bartolomeu','Beatriz','Belchior','Belmifer','Belmiro','Belmira','Benedita','Benedito','Bento','Berengária','Berengário','Bernardete','Bernardo','Bernardina','Bernardino','Bibiana','Blasco','Boaventura','Borrás','Branca','Branco','Brás','Brenda','Bukake','Bráulio','Brígida','Brites','Bruna','Bruno','Basilio','Caetana','Caetano','Caio','Caím','Camila','Camilo','Cândida','Cândido','Capitolina','Capitolino','Carina','Carine','Carla','Carminda','Calisto','Carlos','Carlota','Carmem','Carolina','Casimiro','Catarina','Catarino','Cassandra','Cássia','Cássio','Cátia','Cecília','Celeste','Celestina','Celestino','Célia','Celina','Celso','César','Cesário','Cid','Cidália','Clara','Clarindo','Clarisse','Claudemira','Claudemiro','Cláudia','Cláudio','Cleiton','Clementina','Clotilde','Clóvis','Collin','Comecus','Conceição','Conrado','Constança','Constantino','Cora','Corina','Cosme','Cosperranho','Crispim','Cristiana','Cristiano','Cristina','Cristóvão','Custódio','Cleusa','Dália','Dalila','Damião','Daniel','Daniela','Danilo','David','Davide','Débora','Deise','Delfim','Delfina','Délia','Délio', 'Denise','Deolinda','Deolindo','Derli','Diamantino','Diana',
    'Diogo','Dina','Dinarte','Dino','Dinis','Diodete','Diógenes','Dionísio','Dirceu','Domingas','Domingos','Donata','Donato','Dora','Dorindo','Doroteia','Duarte','Dulce','Edgar','Edite','Edmundo','Eduarda','Eduardo','Egas','Eládio','Elba','Elias','Elia','Elisa','Eliseu','Elisabete','Eloi','Elsa','Élvio','Elvira','Ema','Emanuel','Emanuela','Emídio','Emília','Emílio','Emiliana','Emiliano','Énia','Enilda','Epaminondas','Epifânia','Érica','Érico','Ermelinda','Ernesto','Esmeralda','Esperança','Estanislau','Estefânia','Estela','Ester','Estêvão','Eudes','Eugénia','Eugénio','Eulália','Eunice','Eurico','Eusébio','Eva','Evandro','Evangelista','Evaristo','Ezequiel','Fábia','Fabiana','Fabiano','Fábio','Fabíola','Fabrício','Fátima','Faustino','Fausto','Felícia','Feliciana','Feliciano','Felicidade','Felisbela','Félix','Ferdinando','Fernanda','Fernando','Fernão','Filena','Filipa','Filipe','Filinto','Filomena','Fiona','Firmina','Firmino','Flamínia','Flávia','Flávio','Flor','Flora','Florbela','Florêncio','Floriano','Florinda','Floripes','Francisca','Francisco','Frederica','Frederico','Frutuoso','Fulvio','Gabriel','Gabriela','Galindo','Garibaldo','Gaspar','Gastão','Gaudêncio','Gávio','Gedeão','Genoveva','Geraldo','Gerardo','Germana','Germano','Gerson','Gertrudes','Gerusa','Gil','Gilda','Gilberto','Ginéculo','Gina','Giovana','Girão','Gisela','Gláuber','Gláucia','Gláucio','Glauco','Glória','Godo','Godofredo','Godinho ou Godim','Gomes','Gonçalo','Graça','Graciano','Gregório','Greice','Guadalupe','Gualdim','Guálter','Walter','Gueda','Gui','Guida','Guido','Guilherme','Guilhermina','Guiomar','Gustavo','Guterre','Hedviges','Helena','Heitor','Hélia','Hélio','Hélder','Heloísa','Henrique','Henriqueta','Herberto','Heriberto','Herculano','Hermano','Hermenegildo','Hermesinda','Hermígio','Hernâni','Higino','Hipólito','Honorina','Honório','Horácio','Hugo','Humberto','Ingrit','Iara','Ifigénia','Ildefonso','Ilduara','Ilídio','Ilma','Inês','Inácio','Iolanda','Irene','Íris','Isaac Isaque','Isabel','Ismael','Israel','Isadora','Isaura','Isidro','Isilda','Ítala','Ítalo','Iva','Ivete','Ivo','Iuri','Jacinta','Jacinto','Jadir','Jaime','Jéssica','Jeremias','Joana','João','Joaquim','Joaquina',
    'Jerónimo','Joel','Jonas','Jónatas','Jordana','Jordão','Jorge','Jorgina','José','Josefa','Josefina','Josias','Josué','Judá','Judas','Judite','Júlia','Júlio','Juliano','Julieta','Justino','Lara','Laura','Laurinda','Lavínia','Lázaro','Leandro','Liedson','Léia','Lénia','Leonardo','Leonel','Leónidas','Leonilde','Leonir','Leonor','Leopoldina','Leopoldo','Letícia','Levi','Levindo','Lídia','Lígia','Lívia','Lília','Liliana','Lina','Liana','Lineu','Lina','Lino','Lopo','Lorena','Lourenço','Lua','Luana','Luís','Luísa','Luize','Lúcia','Lúcio','Luciana','Luciano','Lucinda','Lucília','Lucílio','Lucrécia','Ludovico','Lurdes','Luzia','Laís','Melinda','Madalena','Mafalda','Magali','Magda','Mamede','Manuel','Manuela','Mara','Márcia','Márcio','Marco','Marcos','Marcela','Marcelo','Margarida','Maria','Mariana','Mariano','Marilda','Marília','Marina','Mário','Marisa','Marlene','Marli','Marta','Martim','Martinho','Mateus','Matias','Matilde','Maurício','Maura','Mauro','Máxima','Máximo','Maximiliano','Mécia','Melissa','Mem','Mercedes','Miguel','Miguelina','Milena','Mileide','Milu','Micael','Micaela','Minervina','Miriam','Moisés','Mónica','Morgana','Murilo','Miru','Nádia','Napoleão','Natacha','Natália','Natividade','Nazaré','Nelson','Nestor','Neusa','Neuza','Nicanor','Nicolas','Nicolau','Nídia','Nilza','Nivaldo','Noé','Noel','Noémia','Norberto','Nuno','Odete','Odilia','Ofélia','Olavo','Olívia','Olívio','Oliveira','Olga','Ondina','Ordonho','Orestes','Oriana','Otávio','Otília','Óscar','Osvaldo','Ovídio','Palo','Palmira','Palmiro','Pandora','Parcidio','Pascoal','Poliana','Patrícia','Patrício','Paulina','Paulino','Paula','Paulo','Paulino','Pedro','Penélope','Piedade','Plácido','Plínio','Políbio','Polibe','Porfírio','Priscila','Querubim','Querubina','Quévin','Quintiliana','Quintiliano','Quintilien','Quintino','Quirina','Quirino','Quitéria','Quitério','Rafael','Rafaela','Ramão','Ramiro','Raimundo','Raquel','Raul','Rebeca','Regina','Reginaldo','Reinaldo','Remo','Renan','Renata','Renato','Ricardina','Ricardo','Rita','Roberta','Roberto','Rodolfo','Rodrigo','Rogério','Romão','Romano','Rómulo','Ronaldo','Roque','Roquita','Rosa','Rosália','Rosalina','Rosalinda','Rosana','Rosário','Rosaura','Roseli','Rúben','Rubim','Rudi','Rufus','Rui','Rute','Ruca','Sonás','Sabina','Sabino','Sabrina','Salomão','Salomé','Salvador','Salvina','Samuel','Sancha','Sancho','Sandoval','Sandra','Sandro','Santiago','Sara','Sarita','Saul','Sebastiana','Sebastião','Selma','Serafim','Serafina','Sérgio','Severino','Sidónio','Silvana','Silvano','Silvério','Sílvia','Sílvio','Simão','Simeão','Simone','Siquenique','Socorro','Soeiro','Sofia','Solange','Solano','Sónia','Soraia','Suniário',
    'Susana','Tadeu','Tainá','Taíssa','Tairine','Tália','Talita','Tânia','Tarrataca','Tatiana','Telma','Telmo','Telo','Teodorico','Teodoro','Teodora','Tércio','Teresa','Teresina','Tiago','Timóteo','Tobias','Tomás','Tomásia','Tomé','Tibúrcio','Trajano','Tristão','Ubaldo','Udo','Ulisses','Ulrico','Umbelina','Urânia','Urbano','Uriel','Úrsula','Valdeci','Valdemar','Valentim','Valentina','Valéria','Valério','Valmor','Vanda','Vanderlei','Vanessa','Vânia','Vasco','Vera','Veridiana','Veridiano','Veríssimo','Verónica','Vicente','Violeta','Viridiana','Viridiano','Vítor ou Victor','Vitória','Virgília','Virgílio','Virgínia','Viriato','Vivaldo','Viviana','Vlademiro','Xavier','Xénia','Xerxes','Ximena','Ximeno','Xico','Xisto','Zacarias','Zara','Zeferino','Zenaide','Zélia','Zidane','Zilda','Zita','Zoe','Zoraide','Zózimo','Zubaida','Zuleica','Zuleide','Zulmira','Zuriel');
    $last_names = array('Abreu','Almeida','Alves','Amaral','Amorim','Andrade','Anjos','Antunes','Araujo','Assuncao','Azevedo','Baptista','Barbosa','Barros','Batista','Borges','Branco','Brito','Campos','Cardoso','Carneiro','Carvalho','Castro','Coelho','Correia','Costa','Cruz','Cunha','Domingues','Esteves','Faria','Fernandes','Ferreira','Figueiredo','Fonseca','Freitas','Garcia','Gaspar','Gomes','Goncalves','Guerreiro','Henriques','Jesus','Leal','Leite','Lima','Lopes','Loureiro','Lourenco','Macedo','Machado','Magalhaes','Maia','Marques','Martins','Matias','Matos','Melo','Mendes','Miranda','Monteiro','Morais','Moreira','Mota','Moura','Nascimento','Neto','Neves','Nogueira','Nunes','Oliveira','Pacheco','Paiva','Pereira','Pinheiro','Pinho','Pinto','Pires','Ramos','Reis','Ribeiro','Rocha','Rodrigues','Sa','Santos','Silva','Simoes','Soares','Sousa','Tavares','Teixeira','Torres','Valente','Vaz','Vicente','Vieira');
    $tlds = array("com","net","gov","org","edu","biz","info",'pt');
    
    $users = array();
    
    for($i = 0; $i<$num; $i++){
      $name = $first_names[rand(0,count($first_names)-1)].' '.$last_names[rand(0,count($last_names)-1)];
      $email = strtolower(str_replace(' ','.',$name)).'@sapo.pt';
      $users[$i] = array('name'=>$name,'email'=>$email);
    }
    
    return $users;
    
  }

  function update_colors($limit,$offset){
    $query = $this->db->get_where('image', array('image_color_ranges' => null),$limit,$offset);
    $res = $query->result();

    foreach ($res as $image) {
      $img_data = ImageProcessing::GetImageInfo($this->config->item('app_path').$image->image_path);

      $data = array(
         'image_palette' => json_encode($img_data->relevantColors),
         'image_color_ranges' => json_encode($img_data->relevantColorRanges)
      );

      $this->db->where('image_id', $image->image_id);
      $this->db->update('image', $data);

      var_dump($img_data->path);
    }
  }

}
?>