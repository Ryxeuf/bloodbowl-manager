import {
    ArrayField,
    ArrayInput, AutocompleteInput, ChipField,
    Edit, Labeled, NumberField, NumberInput,
    ReferenceField, ReferenceInput,
    SimpleForm,
    SimpleFormIterator, SingleFieldList,
    TextField,
    TextInput,
    usePermissions, useRecordContext
} from "react-admin";
import {Box, Grid, Typography} from "@mui/material";
import React from "react";


const PositionInfosField = props => {
    if (props.renderType === 'number') {
        return <Labeled label={props.label}><NumberField source={props.source}/></Labeled>
    }
    if (props.renderType === 'array') {
        return <Labeled label={props.label}><ArrayField source={props.source} {...props}>
            <SingleFieldList linkType={false}>
                <ChipField source="name" size="small"/>
            </SingleFieldList>
        </ArrayField></Labeled>
    }

    return <Labeled label={props.label}><TextField source={props.source}/></Labeled>
}

const TeamConfiguration = props => {
    const record = useRecordContext();

    return <Box display="flex">
        <Box flex={1} mr="0.5em">
            <NumberInput
                source="rerolls"
                helperText={record.rerolls+' / '+record.rerollMax+' ('+new Intl.NumberFormat('fr-FR').format(record.rerollCost)+')'}
                max={record.rerollMax}
                min={0}
            />
        </Box>
        <Box flex={1} ml="0.5em">
            <NumberInput
                source="assistantCoaches"
                helperText={record.assistantCoaches+' / '+record.assistantCoachesMax+' ('+new Intl.NumberFormat('fr-FR').format(record.assistantCoachesCost)+')'}
                max={record.assistantCoachesMax}
                min={0}
            />
        </Box>
        <Box flex={1} ml="0.5em">
            <NumberInput
                source="cheerleaders"
                helperText={record.cheerleaders+' / '+record.cheerleadersMax+' ('+new Intl.NumberFormat('fr-FR').format(record.cheerleadersCost)+')'}
                max={record.cheerleadersMax}
                min={0}
            />
        </Box>
        <Box flex={1} ml="0.5em">
            <NumberInput
                source="dedicatedFans"
                helperText={record.dedicatedFans+' / '+record.dedicatedFansMax+' ('+new Intl.NumberFormat('fr-FR').format(record.dedicatedFansCost)+')'}
                max={record.dedicatedFansMax}
                min={0}
            />
        </Box>
        <Box flex={1} ml="0.5em">
            <NumberInput
                source="apothecary"
                helperText={record.apothecary+' / '+record.apothecaryMax+' ('+new Intl.NumberFormat('fr-FR').format(record.apothecaryCost)+')'}
                max={record.apothecaryMax}
                min={0}
            />
        </Box>
    </Box>;
}

export const TeamEdit = props => {
    const {permissions} = usePermissions();

    return <Edit {...props} redirect='edit'>
        <SimpleForm>
            <Grid container width={"100%"} spacing={2}>
                <Grid item xs={12}>
                    <Typography variant="h6" gutterBottom>
                        Équipe
                    </Typography>
                    <Box display="flex">
                        <Box flex={1} mr="0.5em">
                            <ReferenceField reference="factions" source="faction" link="show">
                                <PositionInfosField source="name"/>
                            </ReferenceField>
                        </Box>
                        <Box flex={1} ml="0.5em">
                            {permissions?.indexOf('ROLE_ADMIN') !== -1 &&
                                <ReferenceField reference="users" source="user">
                                    <PositionInfosField source="username"/>
                                </ReferenceField>
                            }
                        </Box>
                    </Box>
                    <Box display="flex">
                        <Box flex={1} mr="0.5em">
                            <PositionInfosField source="playType"/>
                        </Box>
                        <Box flex={1} ml="0.5em">
                            <PositionInfosField source="playCategory"/>
                        </Box>
                        <Box flex={1} ml="0.5em">
                            <PositionInfosField source="treasury" renderType="number"/>
                        </Box>
                        <Box flex={1} ml="0.5em">
                            <PositionInfosField source="teamValue" renderType="number"/>
                        </Box>
                    </Box>
                    <Box display="flex">
                        <TextInput source={"name"} fullWidth/>
                    </Box>
                    <TeamConfiguration />
                </Grid>
                <Grid item xs={12}>
                    <Typography variant="h6" gutterBottom>
                        Joueurs
                    </Typography>
                    <PlayersForm/>
                </Grid>
            </Grid>
        </SimpleForm>
    </Edit>
};

const PlayersForm = props => {
    const record = useRecordContext();

    // console.log(record)
    function onPositionChange(positionIri, data) {
        // console.log(positionIri)
        // console.log(data)
    }

    return <ArrayInput source="players" reference="players" name="players">
        <SimpleFormIterator inline disableReordering>
            <NumberInput source='number' />
            <TextInput source='name'/>
            <ReferenceInput source={"position"} reference={'positions'} name={'position'} filter={{faction: [record.faction]}}>
                <AutocompleteInput optionText="name" onChange={onPositionChange}/>
            </ReferenceInput>
            <PositionInfosField label="name" source="positionInfos.name"/>
            <PositionInfosField label="m" source="positionInfos.m"/>
            <PositionInfosField label="f" source="positionInfos.f"/>
            <PositionInfosField label="ag" source="positionInfos.ag"/>
            <PositionInfosField label="cp" source="positionInfos.cp"/>
            <PositionInfosField label="ar" source="positionInfos.ar"/>
            <PositionInfosField label="cost" source="positionInfos.cost" renderType="number"/>
        </SimpleFormIterator>
    </ArrayInput>
};