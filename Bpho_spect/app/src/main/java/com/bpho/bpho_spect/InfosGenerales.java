package com.bpho.bpho_spect;

import android.content.Intent;
import android.os.Bundle;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.text.Html;
import android.view.View;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.TextView;

/**
 * Created by Thomas on 19/03/2016.
 */
public class InfosGenerales extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.layout_general_info);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        getSupportActionBar().setTitle(getString(R.string.generalInfos));
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        TextView tv = (TextView) findViewById(R.id.TVgeneralInfos);
        tv.setText(Html.fromHtml("<b>Des fondateurs</b><br>" +
                "<br>" +
                "C'est en septembre 2002 que Clare Roberts et Roger Bausier, avec l'aide d'Antonio Vilardi, créèrent de toute pièce cet ensemble symphonique. Clare en fut l'instigatrice et la première gérante, jusqu'en 2008 ; elle continue à y jouer au pupitre des cors.\n" +
                "<br>" +
                "<br>" +
                "Roger Bausier était le Président de l'asbl Presto Vivace qui gère cette entité, en même temps que Directeur artistique et Chef permanent du BPO, jusqu'à son décès le 20 août 2012. Antonio Vilardi, Directeur du Théâtre Saint-Michel à Etterbeek, offrit dès le début l'hospitalité pour les concerts et les répétitions des concerts donnés en ce lieu, et ce jusqu'en 2011, mais à partir de novembre 2011 il produit ses concerts au Conservatoire Royal de Bruxelles et dans d'autres salles du pays.\n" +
                "<br>" +
                "<br>" +
                "<b>Du Brussels Philharmonic Orchestra</b>" +
                "<br>" +
                "<br>" +
                "Le « Brussels Philharmonic Orchestra » poursuit le but d’offrir aux diplômés des conservatoires la possibilité de mettre en pratique leurs études musicales en faisant partie d’un orchestre symphonique complet et permanent, et ainsi, de se perfectionner et de se professionnaliser.\n" +
                "<br>" +
                "<br>" +
                "La disparition progressive de grands orchestres sur la scène belge a pour conséquence que ces musiciens de qualité n’ont pendant longtemps pas l’occasion de pratiquer leur art comme musicien d’orchestre, et cette situation risque d’être fatale pour leur aptitude artistique. Le BPO a développé sa propre voie et il est devenu une réalité confirmée dans la vie artistique de la Belgique.\n" +
                "<br>" +
                "<br>" +
                "C’est ainsi que le Brussels Philharmonic Orchestra, pour préparer ses concerts se réunit régulièrement et fréquemment pour répéter les grandes oeuvres du répertoire classique et d’autres plus modernes, avec une attention soutenue pour des compositeurs belges ; le but est de maintenir dans le mouvement, au meilleur niveau de leur forme, une centaine de jeunes musiciens, qui sont épaulés par des instrumentistes plus chevronnés, l’ensemble étant dirigé par Roger Bausier, Professeur au Conservatoire Royal de Bruxelles. Les organisateurs recherchent également à promouvoir des jeunes solistes belges, en leur donnant la possibilité de jouer avec un grand orchestre constitué.\n" +
                "<br>" +
                "<br>" +
                "Le Brussels Philharmonic Orchestra a vu croître sa réputation par sa sélection pour des événements de prestige tels que les concerts annuels du « Cocérome » pour la célébration du Traité de Rome, les concerts annuels pour la Journée Internationale des Droits de l’Enfant, les concerts COFENA à Anvers et les Concours annuels de Direction d’orchestre du Conservatoire Royal de Bruxelles.\n" +
                "<br>" +
                "<br>" +
                "L’ensemble se produit régulièrement avec des solistes de renom international dans des salles telles que le Palais des Beaux-Arts et le Conservatoire Royal de Bruxelles, la salle Reine Elisabeth à Anvers, le Concertgebouw de Bruges, le Casino/Kursaal d’Ostende, le Nouveau Siècle à Lille, le Palais des Congrès à Paris, le Théâtre Royal de la Monnaie à Bruxelles, le Cultureel Centrum de Hasselt, le Théâtre Royal de Namur, le Stadsschouwburg à Courtrai, etc…\n" +
                "<br>" +
                "<br>" +
                "Les musiciens du Brussels Philharmonic Orchestra proviennent de vingt-six pays et quatre continents, avec forcément une prédominance de la nationalité belge, en provenance des trois régions et des deux communautés. Ils sont portés par leur enthousiasme pour la musique qui, par la beauté et l’émotion qu’elle exprime, est un instrument d’amour et de paix entre les peuples et les communautés.\n" +
                "<br>" +
                "<br>" +
                "Le Brussels Philharmonic Orchestra est à la recherche de possibilités de prestations, aussi pour les années à venir, en formation complète ou en ensemble plus restreint, tant pour des événements officiels que pour des commanditaires privés (sociétés, groupements, associations, communautés, etc.). Ainsi il pourrait élargir son audience et encore mieux remplir sa mission d progrès social et culturel.\n" +
                "<br>" +
                "<br>" +
                "<b>Des Chefs d'orchestre</b><br>" +
                "<br>" +
                "<br>" +
                "Après le décès en août 2012 du Chef Permanent, Roger BAUSIER, deux chefs se sont partagés la direction des concerts du BPO : Thanos ADAMOPOULOS, et David NAVARRO TURRES. Depuis le 1/1/2014, David NAVARRO TURRES est le chef d'orchestre principal et conseiller musical.\n" +
                "<br>" +
                "<br>" +
                "<b>De l'ASBL Presto Vivace</b><br>" +
                "<br>" +
                "<br>" +
                "L'ASBL Presto Vivace est gérante du Brussels Philharmonic Orchestra. Elle a pour objet social «d’offrir aux jeunes musiciens, notamment des étudiants des Conservatoires, d’acquérir ou d’approfondir, entourés d’ex-professionnels de notoriété, une expérience du répertoire symphonique dans un ensemble orchestral, et en ce sens de promouvoir la musique orchestrale, symphonique ou de chambre, dans le cadre d’activités ou évènements organisés à des fins sociales, caritatives et/ou culturelles ».\n" +
                "<br>" +
                "<br>" +
                "Son siège est sis au n°38, rue Général Patton à 1050 Bruxelles.\n" +
                "<br>" +
                "<br>" +
                "N° d'entreprise: 0480.611.145\n" +
                "<br>" +
                "<br>" +
                "Son Conseil d'administration est composé comme suit:\n" +
                "<br>" +
                "<br>" +
                "- Monsieur Klaus Heinemann, Président\n" +
                "<br>" +
                "<br>" +
                "- Monsieur André Gheeraert\n" +
                "<br>" +
                "<br>" +
                "- Monsieur Jean Hombert\n" +
                "<br>" +"<br>" +
                "- Madame Martine Lederrey (en congé)\n" +
                "<br>" +"<br>" +
                "- Madame Clare Roberts\n" +
                "<br>" +"<br>" +
                "- Monsieur Roland Dewulf\n" +
                "<br>" +"<br>" +
                "- Monsieur David Navarro-Turres\n" +
                "<br>" +"<br>" +
                "- Monsieur Loeurng Hiroshi.\n" +
                "<br>" +"<br>" ));
        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(InfosGenerales.this, Contact.class);
                startActivity(intent);
            }
        });
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                onBackPressed();
                break;
        }
        return super.onOptionsItemSelected(item);
    }
}
