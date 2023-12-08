import {
    ArrayInput, AutocompleteInput,
    Edit, NumberInput,
    ReferenceField, ReferenceInput,
    SimpleForm,
    SimpleFormIterator,
    TextField,
    TextInput,
    usePermissions, useRecordContext
} from "react-admin";

export const TeamEdit = props => {
    const {permissions} = usePermissions();

    return <Edit {...props}>
        <SimpleForm>
            <ReferenceField reference="factions" source="faction" link="show">
                <TextField source="name"/>
            </ReferenceField>
            {permissions?.indexOf('ROLE_ADMIN') !== -1 &&
                <ReferenceField reference="users" source="user" link="show">
                    <TextField source="username"/>
                </ReferenceField>
            }
            <TextInput source={"name"}/>
            <TextInput source={"playType"} disabled={true}/>
            <TextInput source={"playCategory"} disabled={true}/>
            <PlayersForm/>
        </SimpleForm>
    </Edit>
};

const PlayersForm = props => {
    const record = useRecordContext();

    return <ArrayInput source="players" reference="players">
        <SimpleFormIterator inline>
            <NumberInput source='number'/>
            <TextInput source='name'/>
            <ReferenceInput source={"position"} reference={'positions'} name={'position'} filter={{faction: [record.faction]}}>
                <AutocompleteInput optionText="name"/>
            </ReferenceInput>
        </SimpleFormIterator>
    </ArrayInput>
};