# CUERPO-SANO

🔹 Ramas que vas a tener

main → la principal (ya existe).

agustin → tu rama personal (por ejemplo con tu nombre).

test → para pruebas generales.

lucas → para tu compañero.

🔧 1. Crear las ramas en tu repo local

Ejecutá en tu carpeta del proyecto:

# Rama personal
git branch agustin

# Rama de test
git branch test

# Rama para tu compañero
git branch lucas

🔧 2. Subir las ramas al remoto (GitHub)
git push -u origin agustin
git push -u origin test
git push -u origin lucas

🔧 3. Cambiar de rama cuando quieras trabajar

Por ejemplo, para moverte a tu rama:

git checkout agustin


Para ir a la de test:

git checkout test

🔧 4. Flujo de trabajo recomendado

Cada uno trabaja en su rama (aj o compa).

Para probar cosas conjuntas, se mezclan en test.

Cuando todo está validado, se hace un merge a main.
