<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'SpeedApp') }}</title>

        <link rel="icon" href="{{ url('oldfavicon.ico') }}">
    
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    </head>
    <body class="font-sans antialiased overflow-x-hidden bg-gray-50">

        <div class="min-h-full flex flex-col justify-center py-6 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-7xl">
                <a href="{{ route('welcome') }}">
                    <img class="mx-auto h-28 w-auto" src="{{ asset('images/logo.svg') }}" alt="SpeedApp"/>
                </a>

                <h2 class="text-center text-xl font-medium text-gray-800">CONTRATO DE PRESTAÇÃO DE SERVIÇOS PARA ENTREGADORES</h2>
            </div>
    
            <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-7xl">
                <div class="flex flex-col items-center py-8 px-4 sm:px-10">
                    <p class="text-lg text-gray-800 mt-2">
                        Pelo presente instrumento particular, têm entre si ajustado CONTRATO DE PRESTAÇÃO DE SERVIÇOS, cujo cumprimento se obrigam mutuamente, de um lado, como CONTRATANTE, e assim doravante denominada, SPEEDAPP MARKETPLACE LTDA., pessoa jurídica de direito privado, CNPJ 38.120.958/0001-99 e endereço R. Bernardo Dornbusch, 1106 - Vila Lalau, Jaraguá do Sul - SC, 89256-184, e de outro lado, como CONTRATADA(O) e assim doravante denominada, usuário devidamente cadastro no aplicativo SpeedApp Para Entregadores, mediante as cláusulas a seguir, e considerando que:
                        <br/>
                        <br/>
                        i.	a CONTRATANTE é proprietária de plataforma online, disponível por meio do endereço eletrônico https://speedapp.com.br e por meio de aplicativo para dispositivos com sistemas operacionais iOS e Android, neste instrumento, a partir de agora denominados simplesmente PLATAFORMA, e que permitem a realização de pedidos de entrega de produtos em domicílio para estabelecimentos devidamente cadastros no sistema;
                        <br/>
                        ii.	a CONTRATANTE é legítima titular da marca SPEEDAPP, com registro junto ao Instituto Nacional da Propriedade Industrial – INPI, sob o nº 99999999, cujos nome e sinais distintivos são de utilização exclusiva seu;
                        <br/>
                        iii. a CONTRATANTE exerce sua atividade também por meio de entregadores terceirizados, ora denominados CONTRATADOS;
                        <br/>
                        iv.	a(o) CONTRATADA(O) realizou seu cadastro na PLATAFORMA e afirma expressamente, neste ato, que todos os dados lá cadastrados são verdadeiros, reconhecendo que qualquer erro de cadastro, especialmente quanto à conta bancária utilizada para seu pagamento, eximirá a CONTRATANTE de qualquer responsabilidade;
                        <br/>
                        v.	a(o) CONTRATADA(O) afirma que leu todos os termos de uso, políticas de privacidade ativos na PLATAFORMA, especialmente os referentes aos consumidores;
                        <br/>
                        vi.	a(o) CONTRATADA(O) afirma que conhece e entende a sistemática de funcionamento da PLATAFORMA, especialmente no que se refere aos pedidos dos clientes e como ocorre a seleção dos entregadores; e
                        <br/>
                        vii. a(o) CONTRATADA(O) reconhece e tem consciência de todos os riscos envolvidos na prestação dos serviços, especialmente, mas não só, aqueles decorrentes do trânsito, sendo seu o ônus referente a toda e qualquer despesa e prejuízo decorrentes da prestação do serviço, exonerando a CONTRATANTE de qualquer responsabilidade, já que a sua adesão à PLATAFORMA é feita de livre e espontânea vontade.
                        <br/>
                        <br/>
                        1. OBJETO.
                        <br/>
                        <br/>
                        1.1. A(O) CONTRATADA(O) obriga-se a prestar os serviços de entrega de mercadorias para a CONTRATANTE ou empresas parceiras das CONTRATANTE, identificadas, distribuídas e localizadas por meio da PLATAFORMA.
                        <br/>
                        <br/>
                        1.2. A(O) CONTRATADA(O), durante a vigência deste contrato, terá acesso gratuito à PLATAFORMA para concorrer, de forma virtual e conforme seu próprio interesse e disponibilidade, à entrega de produtos indicados pela CONTRATANTE ou seus parceiros.
                        <br/>
                        <br/>
                        2. DA EXECUÇÃO DO OBJETO.
                        <br/>
                        <br/>
                        2.1. A(O) CONTRATADA(O) obriga-se a prestar os serviços relacionados no item 1 de forma pessoal, não podendo terceirizar ou ceder a qualquer outra pessoa os direitos e obrigações decorrentes deste contrato.
                        <br/>
                        <br/>
                        2.2. A oferta e seleção das entregas à(ao) CONTRATADA(O) dependem do âmbito territorial de operação que ele se encontre e da capacidade de entrega dos produtos requisitados pelo cliente, cabendo à CONTRATANTE definir, por meio de seu sistema operacional, a seleção inicial dos entregadores.
                        <br/>
                        <br/>
                        2.3. Ao aceitar o pedido, a(o) CONTRATADA(O) será notificado pela PLATAFORMA a respeito dos produtos solicitados, modo e lugar de entrega, as quais são de cumprimento obrigatório da(o) CONTRATADA(O).
                        <br/>
                        <br/>
                        2.4. No momento da notificação do serviço que lhe for disponibilizada a(o) CONTRATADA(O) poderá recusar o serviço, de forma unilateral, momento em que a PLATAFORMA notificará outro entregador habilitado na região.
                        <br/>
                        <br/>
                        2.4.1. A PLATAFORMA poderá notificar vários entregadores ao mesmo tempo e, neste caso, o primeiro que aceitar será o responsável pela entrega.
                        <br/>
                        <br/>
                        2.5. A atividade referida no presente instrumento não possui caráter de exclusividade, ficando livre à(ao) CONTRATADA(O) prestar os mesmos serviços a outras empresas ou entidades, concomitantemente ou não, desde que não utilize material ou identificação da CONTRATANTE.
                        <br/>
                        <br/>
                        2.6. A(O) CONTRATADA(O) obriga-se a desempenhar os serviços contratados com o cuidado e zelo devidos, de acordo com a demanda apresentada, e atender às regras de educação e civilidade tanto com as empresas parceiras quanto com os clientes e consumidores da PLATAFORMA.
                        <br/>
                        <br/>
                        2.7. A(O) CONTRATADA(O) obriga-se, igualmente, enquanto estiver exercendo atividades decorrentes deste contrato, a respeitar e seguir todas as normas e regulamentos de trânsito, preservando o bom nome da CONTRATANTE.
                        <br/>
                        <br/>
                        3. DOS MATERIAIS UTILIZADOS.
                        <br/>
                        <br/>
                        3.1. Fica certo e ajustado que os equipamentos de segurança necessários à execução dos serviços ora contratados, nos termos das legislações de trânsito, ficarão a cargo da(o) CONTRATADA(O).
                        <br/>
                        <br/>
                        3.2. A(O) CONTRATADA(O) receberá, para o cumprimento do objeto deste contrato, uma bolsa térmica (especificar qualificação) em bom estado de conservação, devendo devolvê-la nas mesmas condições ao término da atividade diretamente a de quem recebeu, seja a CONTRATANTE ou empresa parceira.
                        <br/>
                        <br/>
                        4. REMUNERAÇÃO.
                        <br/>
                        <br/>
                        4.1. A(O) CONTRATADA(O) receberá da CONTRATANTE pelos serviços aqui descritos o valor das taxas de entrega aceitas no aplicativo, a ser liquidado até o dia 10 (dez) do mês subsequente ao da prestação dos serviços, por meio de crédito na sua conta na PLATAFORMA.
                        <br/>
                        <br/>
                        4.2. A(O) CONTRATADA(O) autoriza expressamente que a CONTRATANTE retenha quaisquer importâncias tributárias ou não determinadas pelo poder público, assim como decorrentes de avarias no material transportado, inclusive no equipamento previsto no item 3.2.
                        <br/>
                        <br/>
                        4.3. Na hipótese de não liberação do valor no prazo previsto no item 4.1, serão acrescidos multa de 2% (dois por cento), juros de mora de 1% (um por cento) ao mês e correção monetária pelo INPC até a data do seu efetivo pagamento.
                        <br/>
                        <br/>
                        5. DO PRAZO.
                        <br/>
                        <br/>
                        5.1. O presente contrato vigerá por prazo indeterminado, a contar da sua aceitação pela PLATAFORMA, podendo ser rescindido por qualquer das partes, a qualquer momento, sem aviso prévio.
                        <br/>
                        <br/>
                        5.2. Havendo quaisquer modificações nos termos deste contrato, a(o) CONTRATADA(O) será devidamente notificada na PLATAFORMA, devendo aceitá-las para continuar a prestação de serviços ora ajustada.
                        <br/>
                        <br/>
                        6. DAS RESPONSABILIDADES.
                        <br/>
                        <br/>
                        6.1. O presente contrato regulamenta-se pelas normas do Direito Civil e não gera vínculo empregatício de qualquer natureza entre as partes, em especial ante a autonomia das partes e a inexistência de relação hierárquica, de dependência ou de subordinação, podendo, a(o) CONTRATADA(O), realizar livremente suas atividades, nos momentos que entender oportuno.
                        <br/>
                        <br/>
                        6.1.1. As partes são completamente independentes administrativamente, sendo cada uma responsável pelos seus custos operacionais, inclusive, mas não somente, quanto a despesas tributárias e previdenciárias, de manutenção e combustível dos veículos, taxas diversas.
                        <br/>
                        <br/>
                        6.2. São obrigações da(o) CONTRATADA(O):
                        <br/>
                        <br/>
                        I. Usar a PLATAFORMA única e exclusivamente nos termos definidos neste instrumento;
                        <br/>
                        II. Cumprir com todos os requisitos e obrigações normativa legais vigente aplicáveis a atividade exercida na PLATAFORMA;
                        <br/>
                        III. Prestar informações reais e verdadeiras no cadastro de acesso à PLATAFORMA;
                        <br/>
                        IV. Enviar documentos verdadeiros e atuais à PLATAFORMA no momento do cadastro e atualizá-los sempre que houver alguma mudança;
                        <br/>
                        V. Respeitar todos os elementos de propriedade intelectual da CONTRATANTE, pelo qual se absterá de realizar engenharia reversa, de compilação, derivação de código fonte ou similares que envolvam qualquer sistema da PLATAFORMA ou seus produtos derivados, assim como de utilizar a marca SPEEDAPP sem autorização da CONTRATANTE;
                        <br/>
                        VI. Respeitar incondicionalmente as leis e normas de trânsito e nunca exercer as atividades previstas neste instrumento sob efeito de bebidas alcoólicas, alucinógenos, narcóticos e demais substâncias que possam afetar seu estado psicológico normal;
                        <br/>
                        VII. Estar cadastrado no sistema de previdência social, na qualidade de autônomo, e a manter dita filiação vigente durante a duração da presente relação comercial;
                        <br/>
                        VIII. Manter seu(s) veículo(s) utilizado(s) para a prestação da atividade objeto deste contrato devidamente regularizado(s) e adequado(s) para o transporte de produtos conforme legislação vigente;
                        <br/>
                        IX. Arcar com os custos e ônus de toda e qualquer infração de trânsito ou dano que ocorra em decorrência direta ou indireta do serviço, incluindo, mas não apenas, multas, acidentes, processos judiciais ou administrativos, danos e/ou extravio dos produtos entregues sob sua responsabilidade, sem que a CONTRATANTE seja de qualquer forma responsabilizada por esses eventos;
                        <br/>
                        X. Assumir toda e qualquer obrigação de suportar integralmente eventuais condenações, custos e despesas que forem imputados à CONTRATANTE em decorrência de seus (da(o) CONTRATADA(O)) atos ou omissões que causem prejuízos a terceiros ou à própria CONTRATANTE.
                        <br/>
                        <br/>
                        7. DA AUTORIZAÇÃO DE CESSÃO DE DIREITOS DE IMAGEM
                        <br/>
                        <br/>
                        7.1. A(O) CONTRATADA(O) autoriza expressamente e de forma gratuita e universal a CONTRATANTE a utilizar sua imagem em campanhas e eventos promovidos ou patrocinados por si, enquanto durar a presente contratação, sem qualquer ônus presente ou futuro para a CONTRATANTE.
                        <br/>
                        <br/>
                        7.2. A(O) CONTRATADA(O) reconhece que entende como imagem qualquer forma de representação, inclusive fotográfica ou de vídeo, com ou sem som, e que pode ser utilizada também em redes sociais ou qualquer outro meio ou plataforma na internet.
                        <br/>
                        <br/>
                        8. DO CANCELAMENTO DO ACESSO À PLATAFORMA.
                        <br/>
                        <br/>
                        A(O) CONTRATADO terá o seu acesso cancelado ou bloqueado na PLATAFORMA, dentre outras, nas seguintes situações:
                        <br/>
                        <br/>
                        I. Por descumprimento do presente contrato ou por mera liberalidade da CONTRATANTE;
                        <br/>
                        II. Por má qualidade na prestação de serviço aos clientes e parceiros da CONTRATANTE;
                        <br/>
                        III. Por atos contra a ética e aos bons costumes ou por colocar em risco o nome, a imagem e a reputação da CONTRATANTE ou da PLATAFORMA;
                        <br/>
                        IV. Se estiver respondendo a qualquer processo criminal;
                        <br/>
                        V. Por causar prejuízos ao patrimônio da CONTRATANTE ou de terceiros;
                        <br/>
                        VI. Por usar as marcas, logotipos, nomes e insígnias de propriedade da CONTRATANTE sem autorização prévia e por escrito dela;
                        <br/>
                        VII. Por usar o aplicativo para financiar qualquer atividade ilegal, inclusive lavagem de dinheiro;
                        <br/>
                        VIII. Se estiver sem as licenças e autorizações exigidas pela CONTRATANTE para o uso da PLATAFORMA ou pelo poder público, mesmo que apurado posteriormente a sua autorização de uso da PLATAFORM;
                        <br/>
                        <br/>
                        9. FORO
                        <br/>
                        <br/>
                        9.1. A partes elegem o foro da comarca de Jaraguá do Sul - SC para dirimir as controvérsias oriundas do presente instrumento, renunciando, por mais privilegiado que seja, foro diferente do aqui pactuado.
                        <br/>
                        <br/>
                        E, por assim estarem justos e contratados, aceitam os termos de uso do aplicativo.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
