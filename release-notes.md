# Release Notes

## 17/05/2021
### FORMS
    * Atualizado o endereço de consulta de CEP para utilizar o https
    * Criado uma verificação para remover a inserção do endereço do usuário quando for orçamento
## 16/05/2021
### Solicitação de transporte
    *  Em origem e destino, quando selecionado "meu endereço", busca pelo CEP dos correios
    * Na etapa de envio de documento, ter a opção de desmarcar/limpar um documento selecionado

### Listagem de solicitações (Tela do usuário)
    * ao visualizar uma solicitação, está vindo com erros e com os estilos bugados
    * ver se é possível clicar no item e ir pra ele, sem a necessidade de ter que rolar pra cima e clicar em alterar (isso pode continuar existindo, juntamente com os filtros)
    * order by ID DESC
    * ocultar ID da tabela
    * colocar um hover (efeito de card)
    * deixar maior a tabela
    * buscar listagem por ID e CPF/CNPJ (username)
    * na listagem de solicitações, mostrar um botão chamado "Vistoria" quando campo tiver preenchido

### Admin
    * na listagem de solicitações, incluir campo para incluir link de vistoria (duplicar e colocar antes de observações)

## 02/05/2021
### Admin
        * No menu do admin de solicitações, alterar "Transporte" para "Solicitações"
        * Na listagem de orçamentos e solicitações, informar a hora do item também
        * Nas listagens (orçamento e solicitações) order by ID DESC
        * Nas listagens (orçamento e solicitações), ver se é possível clicar no item e ir pra ele, sem a necessidade de ter que rolar pra cima e clicar em alterar (isso pode continuar existindo, juntamente com os filtros)
        * Nas listagens (orçamento e solicitações) colocar um hover (efeito de card)

### Solicitação de Transporte
        * Centralizar o form
        * Diminuir espaço entre "abas de sinalização e o form" [dados pessoais e do veículo....] deixar 10px
        * Colocar uma margin de 60px no topo e bottom
        * Pegar o título da página
        * Ao avançar as etapas, rolar a página para o topo  
        * Em dados "Dados do Veículo", o campo "Situação do veículo " não está ficando em vermelho na validação;
        * Em "Origem" e "Destino" os campos não estão ficando em vermelho na validação;
        * Abaixo do campo da  "Tabela Fipe" Adicionar link  "Consultar Tabela Fipe": https://veiculos.fipe.org.br/  
        * Remover botão de "área do cliente" na mensagem de sucesso
        * Ao tentar uma solicitação com um usuário que não existe na base, ele está dando erro
    
### Orçamento
        * Centralizar o form
        * Diminuir espaço entre "abas de sinalização e o form" [dados pessoais e do veículo....] * deixar 10px
        * Colocar uma margin de 50px no topo e bottom
        * Ao avançar as etapas, rolar a página para o topo
        * A mensagem de retorno, está aparecendo desde o início ao entrar na página
        * No Campo "Tabela Fipe", aumentar possibilidade para valores acima de R$ 99.999,99 (deixar 3 digitos)
        * Abaixo do campo da  "Tabela Fipe" Adicionar link  "Consultar Tabela Fipe": https://veiculos.fipe.org.br/


## 28/04/2021
    * Removido o outline do focus nos campos obrigatórios
    * Removido o erro do botão no change do input e select
    * Adicionado o título do formulário cadastrado no admin
    * Corrigido o erro no endereço no form de orçamento
    * Adicionado os assuntos nos emails de solicitações
    * Buscando as mensagens de sucesso do cadastro no admin 'mensagem_sucesso_solicitacoes'

## 20/04/2021
    * Criado o formulário de Orçamento
    * Criado a página administrativa para exibir e alterar os orçamento existentes
    * Ajustado a query das solicitações de transporte para não olhar para os orçamentos

## 19/04/2021
    * Removido o cabeçalho e rodapé do modelo de lista de solicitação de transporte
    * Adicionado o cabeçalho e rodapé do modelo de solicitação de transporte
    * Adicionado as importações de js e css no header do template
    * Adicionado botões de voltar na lista de solicitações
    * Ajustado layout da lista de solicitações
    * Adicionado o arquivo `.user.ini` na raiz do projeto

## 18/04/2021
    * Removido o cabeçalho e rodapé do modelo de solicitação de transporte
    * Removido o `outline` do focus nos campos com erros
    * Adicionado focus no cabeçalho na mudança de todas as etapas
    * Adicionado o campo correto para a mascará de CEP
    * Adicionado `required` nos campos de endereço no origem e destino

## 12/04/2021
    * Criado a página de listagem das solicitações dos usuários
    * Criado a página de exibição/alteração da solicitação do usuário

## 10/04/2021
    * Ajustado as informações da solicitação do usuário, na tabela, após utilizar o filtro
    * Ajustado o campo de `status` na atualização da solicitação de transporte
    * Adicionado a mensagem de sucesso após o usuário concluir o preenchimento do formulário de solicitação
    * Adicionado o link no botão de `Área do Cliente` no layout de sucesso

## Tasks
```
[x] Alterar formulário para buscar os dados do usuário e não permitir alteração
[x] Bater os campos com os dados da planilha
[x] Não permitir a alteração nos dados do usuário dentro do plugin
[x] Alterar a tabela de solicitação de transporte para registrar somente os dados do veículo e links das imagens
[ ] Alterar o plugin de solicitação para que quando o usuário adm entre no pedido verifique se há usuários cadastrados com o mesmo cpf
[ ] Criar shortcode para a listagem de pedidos com um campo de observações
[x] Colocar botões para apagar imagens dos documentos
[x] inserir o input file nos campos de documentos
```