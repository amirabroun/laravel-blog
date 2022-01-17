<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* database/structure/drop_form.twig */
class __TwigTemplate_b8748e3441f50d30cad5e9af0e50b5eb955462e08a390bc7a36946192ad6f9ec extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<form action=\"";
        echo PhpMyAdmin\Url::getFromRoute("/database/structure/drop-table");
        echo "\" method=\"post\">
  ";
        // line 2
        echo PhpMyAdmin\Url::getHiddenInputs(($context["url_params"] ?? null));
        echo "

  <fieldset class=\"confirmation\">
    <legend>
      ";
        // line 6
        echo _gettext("Do you really want to execute the following query?");
        // line 7
        echo "    </legend>

    <code>";
        // line 9
        echo ($context["full_query"] ?? null);
        echo "</code>
  </fieldset>

  <fieldset class=\"tblFooters\">
    <div id=\"foreignkeychk\" class=\"floatleft\">
      <input type=\"hidden\" name=\"fk_checks\" value=\"0\">
      <input type=\"checkbox\" name=\"fk_checks\" id=\"fk_checks\" value=\"1\"";
        // line 15
        echo ((($context["is_foreign_key_check"] ?? null)) ? (" checked") : (""));
        echo ">
      <label for=\"fk_checks\">";
        // line 16
        echo _gettext("Enable foreign key checks");
        echo "</label>
    </div>
    <div class=\"floatright\">
      <input id=\"buttonYes\" class=\"btn btn-secondary\" type=\"submit\" name=\"mult_btn\" value=\"";
        // line 19
        echo _gettext("Yes");
        echo "\">
      <input id=\"buttonNo\" class=\"btn btn-secondary\" type=\"submit\" name=\"mult_btn\" value=\"";
        // line 20
        echo _gettext("No");
        echo "\">
    </div>
  </fieldset>
</form>
";
    }

    public function getTemplateName()
    {
        return "database/structure/drop_form.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  78 => 20,  74 => 19,  68 => 16,  64 => 15,  55 => 9,  51 => 7,  49 => 6,  42 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/structure/drop_form.twig", "D:\\laravelWorkspace\\sendMessage\\public\\phpmyadmin\\templates\\database\\structure\\drop_form.twig");
    }
}
